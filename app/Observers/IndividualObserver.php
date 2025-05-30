<?php

namespace App\Observers;

use App\Models\Individual;
use App\Models\Lead;
use App\Models\Sale;
use App\Models\SaleStatus;
use App\Models\Campaign;
use App\Models\User;
use App\Classes\Common;
use Illuminate\Support\Arr;
use Examyou\RestAPI\Exceptions\ApiException;

use function Laravel\Prompts\error;

class IndividualObserver
{
    /**
     * Handle the Individual "saved" event.
     * This will handle the lead_id and sale_id conditionals similar to saveIndividualData
     * 
     * @param  \App\Models\Individual  $individual
     * @return void
     */
    public function saved(Individual $individual)
    {
        $request = request();
        $loggedUser = user();
        if (!$loggedUser) {
            return;
        }
        
        if ($request->sale_id) {
            $saleId = Common::getIdFromHash($request->sale_id);
            $sale = Sale::where('individual_id', $saleId)->first();

            if (!$loggedUser->hasRole('admin') && !$loggedUser->hasPermissionTo('sales_edit')) {
                throw new ApiException("Not Allowed");
            }

            if ($request->has('sale_status_id')) {
                $sale->sale_status_id = $request->sale_status_id;
            }
    
            if ($request->has('assigned_to')) {
                $assignedToXId = $request->assigned_to;
                $assignedToId = Common::getIdFromHash($assignedToXId);
                $sale->assigned_to = $assignedToId;
            }
    
            $sale->save();
        } else if ($request->lead_id) {
            // Lead related logic (similar to saveIndividualData 'lead' type)
            $leadId = Common::getIdFromHash($request->lead_id);
            $lead = Lead::where('individual_id', $leadId)->first();
            
            if ($request->has('lead_status')) {
                $lead->lead_status = $request->has('lead_status') && $request->lead_status != '' ? $request->lead_status : null;
            }

            $individual->last_action_by = $loggedUser->id;

            $lead->save();

            if ($individual->campaign_id) {
                $campaign = Campaign::find($individual->campaign_id);
                if ($campaign) {
                    $campaign->last_action_by = $loggedUser->id;
                    $campaign->save();
                }
            }
        }

        $this->addEditCoapplicant($request);
    }

    public function addEditCoapplicant($request) {
        // Check if co-applicant is required
        if (co_applicant_required() === false) return;

        if ($request->has('co_first_name') && $request->co_first_name !== null) {

            $individualId = Common::getIdFromHash($request->individual_id);
            $individual = Individual::find($individualId);

            if ($individual->coApplicant) {
                $coApplicant = $individual->coApplicant;
            } else {
                $coApplicant = new \App\Models\CoApplicant;
                $coApplicant->individual_id = $individualId;
            }

            $coFields = [
                'first_name' => 'co_first_name',
                'last_name' => 'co_last_name',
                'SSN' => 'co_SSN',
                'date_of_birth' => 'co_date_of_birth',
                'home_phone' => 'co_home_phone',
                'phone_number' => 'co_phone_number',
                'email' => 'co_email',
                'language' => 'co_language',
            ];

            foreach ($coFields as $field => $value) {
                if ($request->has($value)) {
                    $coApplicant->$field = $request->$value;
                }
            }

            $coApplicant->save();
        }
    }
}
