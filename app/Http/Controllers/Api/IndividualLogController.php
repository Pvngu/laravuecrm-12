<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Classes\Common;
use App\Models\IndividualLog;
use App\Http\Controllers\ApiBaseController;
use Examyou\RestAPI\Exceptions\ApiException;
use App\Http\Requests\Api\IndividualLog\IndexRequest;
use App\Http\Requests\Api\IndividualLog\StoreRequest;
use App\Http\Requests\Api\IndividualLog\DeleteRequest;
use App\Http\Requests\Api\IndividualLog\UpdateRequest;

class IndividualLogController extends ApiBaseController
{
    protected $model = IndividualLog::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    protected function modifyIndex($query)
    {
        $user = user();
        $request = request();

        $query = $query->join('individuals', 'individuals.id', '=', 'individual_logs.individual_id')
            ->join('campaigns', 'campaigns.id', 'individuals.campaign_id');

        if (!$user->hasRole('admin') && !$user->hasPermissionTo('leads_view_all')) {
            if ($request->has('log_type') && $request->log_type == 'salesman_bookings') {
                $query = $query->where('individual_logs.created_by_id', $user->id);
            } else if (
                $request->has('log_type') && $request->has('page_name') &&
                $request->page_name == 'lead_action' &&
                $request->has('individual_id') && $request->individual_id != "" &&
                ($request->log_type == 'call_log' || $request->log_type == 'notes' || $request->log_type == 'all')
            ) {
                // When request comes from TakeLeadActionPage
            } else {

                $query = $query->where('individual_logs.user_id', $user->id);
            }
        }

        if ($request->has('log_type') && $request->log_type == 'notes') {
            $query = $query->where('log_type', 'notes');
        }

        if ($request->alert == true) {
            $query = $query->whereNotNull('alert');
        }

        // For Lead Follow Up showing
        // only not actioned leads
        if ($request->has('log_type') && $request->log_type == 'lead_follow_up') {
            $query = $query->where('booking_status', '!=', 'actioned');
        }

        // Extra Filters
        if ($user->hasRole('admin') || $user->hasPermissionTo('view_completed_campaigns')) {
            if ($request->has('campaign_status') && $request->campaign_status == "completed") {
                $query = $query->where('campaigns.status', '=', 'completed');
            } else {
                $query = $query->where('campaigns.status', '!=', 'completed');
            }
        } else {
            $query = $query->where('campaigns.status', '!=', 'completed');
        }

        if ($request->has('individual_id') && $request->individual_id != "") {
            $individualId = $this->getIdFromHash($request->individual_id);

            $query = $query->where('individual_logs.individual_id', $individualId);
        }

        if ($request->has('user_id') && $request->user_id != "") {
            $userId = $this->getIdFromHash($request->user_id);

            $query = $query->where('individual_logs.user_id', $userId);
        }

        if ($request->has('campaign_id') && $request->campaign_id != "") {
            $campaignId = $this->getIdFromHash($request->campaign_id);

            $query = $query->where('individuals.campaign_id', $campaignId);
        }

        if ($request->has('log_type') && $request->log_type == 'call_log') {
            $query = $query->where('individual_logs.time_taken', '>', 0);
        }

        // Dates Filters
        if ($request->has('dates') && $request->dates != "") {
            $dates = explode(',', $request->dates);
            $startDate = $dates[0];
            $endDate = $dates[1];

            $query = $query->whereRaw('individual_logs.date_time >= ?', [$startDate])
                ->whereRaw('individual_logs.date_time <= ?', [$endDate]);
        }

        return $query;
    }

    public function storing($IndividualLog)
    {
        $loggedUser = user();
        $request = request();

        if ($request->log_type == '' || $request->log_type != 'notes') {
            throw new ApiException("Not Allowed");
        }

        $IndividualLog->user_id = $loggedUser->id;
        $IndividualLog->date_time = Carbon::now()->setTimezone('America/Los_Angeles');

        return $IndividualLog;
    }

    public function updating($IndividualLog)
    {
        $request = request();

        if ($request->has('user_id') || $request->has('date_time') || $request->log_type == '' || $request->log_type != 'notes') {
            throw new ApiException("Not Allowed");
        }

        if($request->log_type == 'notes' && $IndividualLog->isDirty('notes')) {
            $xIndividualId = $request->individual_id;
            $individualId = Common::getIdFromHash($xIndividualId);

            $notes = json_encode([
                'notes' => [
                    'old_value' => $IndividualLog->getOriginal('notes'),
                    'new_value' => $request->notes,
                ]
            ]);

            Common::storeIndividualLog($individualId, 'updated_notes', $notes);
        }

        return $IndividualLog;
    }
}
