<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Form;
use App\Models\Lead;
use App\Classes\Common;
use App\Models\Campaign;
use App\Models\Individual;
use App\Imports\LeadImport;
use Illuminate\Support\Str;
use App\Exports\LeadsExport;
use App\Models\CampaignUser;
use App\Models\EmailTemplate;
use Examyou\RestAPI\ApiResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\ApiBaseController;
use Examyou\RestAPI\Exceptions\ApiException;
use App\Http\Requests\Api\Campaign\IndexRequest;
use App\Http\Requests\Api\Campaign\StoreRequest;
use App\Http\Requests\Api\Campaign\DeleteRequest;
use App\Http\Requests\Api\Campaign\UpdateRequest;
use App\Http\Requests\Api\Campaign\StopCampaignRequest;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Http\Requests\Api\Campaign\EmailTemplatesRequest;
use App\Http\Requests\Api\Campaign\SkipDeleteLeadRequest;
use App\Http\Requests\Api\Campaign\TakeLeadActionRequest;
use App\Http\Requests\Api\Campaign\CampaignLeadActionRequest;

class CampaignController extends ApiBaseController
{
    protected $model = Campaign::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    protected function modifyIndex($query)
    {
        $user = user();
        $request = request();

        // Checking use is user of camaign
        if (!$user->hasRole('admin') && !$user->hasPermissionTo('campaigns_view_all')) {
            $query = $query->join('campaign_users', 'campaign_users.campaign_id', '=', 'campaigns.id')
                ->where('campaign_users.user_id', $user->id);
        }

        if ($user->hasRole('admin') || $user->hasPermissionTo('view_completed_campaigns')) {
            // Filter By Campaign Status
            if ($request->has('campaign_status') && $request->campaign_status == "completed") {
                $query = $query->where('status', '=', 'completed');
            } else {
                $query = $query->where('status', '!=', 'completed');
            }
        } else {
            $query = $query->where('status', '!=', 'completed');
        }

        return $query;
    }


    public function storing($campaign)
    {
        $request = request();
        $loggedUser = user();

        $campaign->created_by = $loggedUser->id;
        $campaign->updated_by = $loggedUser->id;
        $campaign->detail_fields = json_decode($request->detail_fields);

        return $campaign;
    }

    public function updating($campaign)
    {
        $loggedUser = user();
        $request = request();

        $campaign->updated_by = $loggedUser->id;
        $campaign->detail_fields = json_decode($request->detail_fields);

        return $campaign;
    }

    public function stored(Campaign $campaign)
    {
        $request = request();
        $allUsers = json_decode($request->user_id);

        foreach ($allUsers as $allUser) {
            $userId = $this->getIdFromHash($allUser);

            $campaignUser = new CampaignUser();
            $campaignUser->user_id = $userId;
            $campaignUser->campaign_id = $campaign->id;
            $campaignUser->save();
        }

        // Saving csv file data
        $this->saveCsvFileData($campaign);
    }

    public function updated(Campaign $campaign)
    {
        $request = request();
        $allUsers = json_decode($request->user_id);
        CampaignUser::where('campaign_id', $campaign->id)->delete();

        foreach ($allUsers as $allUser) {
            $userId = $this->getIdFromHash($allUser);

            $campaignUser = new CampaignUser();
            $campaignUser->user_id = $userId;
            $campaignUser->campaign_id = $campaign->id;
            $campaignUser->save();
        }

        // Saving csv file data
        $this->saveCsvFileData($campaign);
    }

    public function saveCsvFileData($campaign)
    {
        $request = request();

        // Saving csv file data
        if ($request->hasFile('file')) {
            $formId = $campaign->form_id;
            $form = Form::find($formId);
            $formFields = $form && $form->form_fields ? $form->form_fields : [];

            $importLeadFields = json_decode($request->import_lead_fields, true);

            Excel::import(new LeadImport($importLeadFields, $formFields, $campaign->id), request()->file('file'));
        }
    }

    public function stopCampaign(StopCampaignRequest $request)
    {
        $loggedUser = user();
        $xid = $request->x_campaign_id;
        $id = $this->getIdFromHash($xid);

        $campaign = Campaign::find($id);
        $campaign->status = 'completed';
        $campaign->completed_on = Carbon::now()->setTimezone('America/Los_Angeles');
        $campaign->completed_by = $loggedUser->id;
        $campaign->save();

        return ApiResponse::make('Success', []);
    }

    public function takeAction(CampaignLeadActionRequest $request)
    {
        $loggedUser = user();
        $xid = $request->x_campaign_id;
        $id = $this->getIdFromHash($xid);

        $campaign = Campaign::with('form')->find($id);
        // $upcomingLeadAction = $campaign->upcoming_lead_action;

        // Next not started lead
        $lead = $this->upcomingNotStartedLead($campaign->id);

        // No Lead found
        // Creating new lead
        if ($lead == null) {
            $lead = $this->createNewLead($campaign, true);
        }

        if ($campaign->started_on == null) {
            $campaign->started_on = Carbon::now()->setTimezone('America/Los_Angeles');
            $campaign->status = 'started';
        }
        $campaign->last_action_by = $loggedUser->id;
        $campaign->save();

        // Calculating Lead Counts
        Common::recalculateCampaignLeads($campaign->id);
        return ApiResponse::make('Success', [
            'x_lead_id' => $lead->xid,
        ]);
    }

    public function upcomingNotStartedLead($id)
    {
        $loggedUser = user();

        // Taking latest lead
        // which is not-started
        $lead = Lead::join('individuals', 'individuals.id', '=', 'leads.individual_id')
            ->where('individuals.campaign_id', $id)
            ->where('leads.started', 0)
            ->whereNull('individuals.first_action_by')
            ->oldest('leads.id')
            ->select('leads.*')
            ->first();

        if ($lead) {
            $individual = $lead->individual;

            $lead->started = 1;
            $individual->first_action_by = $loggedUser->id;
            $individual->last_action_by = $loggedUser->id;

            $individual->save();
            $lead->save();

            return $lead;
        } else {
            return null;
        }
    }

    public function createNewLead($campaign)
    {
        $loggedUser = user();

        $lead = new Lead();
        $individual = new Individual();
        $individual->campaign_id = $campaign->id;
        // Reference Prefix
        if ($campaign->allow_reference_prefix) {
            $individual->reference_number = $campaign->reference_prefix . Carbon::now()->timestamp;
        }

        if ($campaign->form_id && $campaign->form && $campaign->form->form_fields) {
            $newLeadData = [];
            foreach ($campaign->form->form_fields as $allFormFields) {
                $newLeadData[] = [
                    'id' => strtolower(Str::random(12)),
                    'field_name' => $allFormFields['name'],
                    'field_value' => '',
                ];
            }
            $individual->lead_data = $newLeadData;
        }

        $lead->started = 1;
        $individual->first_action_by = $loggedUser->id;
        $individual->last_action_by = $loggedUser->id;
        $individual->created_by = $loggedUser->id;

        $individual->save();
        $lead->individual_id = $individual->id;
        $lead->save();

        Common::recalculateCampaignLeads($campaign->id);

        return $lead;
    }

    public function takeLeadAction(TakeLeadActionRequest $request)
    {
        $user = user();
        $request = request();
        $actionType = $request->action_type;

        // Saving Current Lead
        $currentLead = Common::saveIndividualData('lead');
        $leadId = $currentLead->id;
        $lead = Common::takeLeadAction($actionType, $leadId, $currentLead->individual->campaign_id);

        return ApiResponse::make('Success', [
            'lead' => $lead ? $lead : null
        ]);
    }

    // Update Lead with data & timer
    // comes from TakeLeadAction.vue Page
    public function updateActionedLead()
    {
        $lead = Common::saveIndividualData('lead');

        return ApiResponse::make('Success', [
            'lead' => $lead
        ]);
    }

    public function updateActionedSale()
    {
        $sale = Common::saveIndividualData('sale');

        return ApiResponse::make('Success', [
            'sale' => $sale
        ]);
    }

    public function updateTimerLead()
    {
        $lead = Common::UpdateLeadTimer();

        return ApiResponse::make('Success', [
            'lead' => $lead
        ]);
    }

    public function skipAndDeleteLead(SkipDeleteLeadRequest $request)
    {
        $xLeadId = $request->lead_id;
        $leadId = $this->getIdFromHash($xLeadId);

        $lead = Lead::find($leadId);
        $campaignId = $lead->campaign_id;
        $lead->delete();

        // Calculating Lead Counts
        Common::recalculateCampaignLeads($campaignId);
        // Getting next lead
        $lead = Common::takeLeadAction('next', $leadId, $campaignId);

        return ApiResponse::make('Success', [
            'lead' => $lead
        ]);
    }

    public function userCampaigns()
    {
        $user = user();

        $userCampaigns = CampaignUser::select('campaign_id')->where('user_id', $user->id)->get();

        return ApiResponse::make('Success', [
            'user_campaigns' => $userCampaigns
        ]);
    }

    public function campaignEmailTemplates(EmailTemplatesRequest $request)
    {
        $user = user();
        $xLeadId = $request->x_lead_id;
        $leadId = $this->getIdFromHash($xLeadId);

        $lead = Lead::find($leadId);

        $userCampaigns = EmailTemplate::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->where('status', 1);
        })
            ->orWhere('')
            ->get();

        return ApiResponse::make('Success', [
            'user_campaigns' => $userCampaigns
        ]);
    }

    public function exportLeads($campaignXId): BinaryFileResponse
    {
        $request = request();
        $user = user();
        
        $columns = (array) $request->columns;
        $format = $request->format;
        $startDate = "";
        $endDate = "";
        $statusIds = $request->status;
        $started = $request->started;

        $campaignId = $this->getIdFromHash($campaignXId);

        if ($request->has('dates') && $request->dates != "") {
            $dates = $request->dates;
            $startDate = $dates[0];
            $endDate = $dates[1];
        }

        if(!$user->hasRole('admin') && !$user->hasPermissionTo('reports_view')) {
            throw new ApiException("Not Allowed");
        }

        Common::storeIndividualLog(null, 'leads_export');

        return Excel::download(new LeadsExport($campaignId, $columns, $startDate, $endDate, $statusIds, $started), "leads.$format");
    }
}
