<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\Lead;
use App\Models\Sale;
use App\Classes\Common;
use App\Models\Campaign;
use App\Models\Individual;
use Examyou\RestAPI\ApiResponse;
use App\Http\Controllers\ApiBaseController;
use Examyou\RestAPI\Exceptions\ApiException;
use App\Http\Requests\Api\Sale\DeleteRequest;
use App\Http\Requests\Api\Sale\CreateSaleRequest;
use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SaleController extends ApiBaseController
{
    protected $deleteRequest = DeleteRequest::class;
    
    protected $model = Sale::class;

    protected function modifyIndex($query) {
        $request = request();
        $user = user();

        // Extra Filter For campaign Type
        $query = $query->join('individuals', 'individuals.id', '=', 'sales.individual_id')
        ->join('campaigns', 'campaigns.id', '=', 'individuals.campaign_id');

        if((!$user->hasRole('admin') && !$user->hasPermissionTo('sales_view_all')) && ($user->hasRole('admin') || $user->hasPermissionTo('sales_view'))) {
            $userId = $request->has('user_id') && $request->user_id != "" ? $request->user_id : $user->id;
            $query = $query->where('assigned_to', $userId);    
        }

        // full_name filter
        if($request->has('lead_field_name') && $request->lead_field_name != "" && $request->has('lead_field_value') && $request->lead_field_value != "") {
            $names = explode(' ', $request->lead_field_value);
            
            $query->where(function($q) use ($names) {
                $q->whereIn('first_name', $names)
                ->orWhere(function($query) use ($names) {
                    $query->whereIn('last_name', $names);
                });
            });
        }

        if($request->has('view_type') && $request->view_type == 'unassigned') {
            $query = $query->whereNull('assigned_to');
        }

        // Dates Filters
        if ($request->has('dates') && $request->dates != "") {
            $dates = explode(',', $request->dates);
            $startDate = $dates[0];
            $endDate = $dates[1];

            $query = $query->whereRaw('sales.created_at >= ?', [$startDate])
                ->whereRaw('sales.created_at <= ?', [$endDate]);
        }

        return $query;
    }

    protected function createSale(CreateSaleRequest $request) {
        $user = user();

        if(!$user->hasRole('admin') && !$user->hasPermissionTo('sales_create')) {
            throw new ApiException("Not Allowed");
        }

        $campaignId = $this->getIdFromHash($request->campaign_id);
        $campaign = Campaign::find($campaignId);

        $xUserId = $request->assigned_user_id;
        $userId = $this->getIdFromHash($xUserId);

        $sale = new Sale();
        $individual = new Individual();
        $lead = new Lead();

        $sale->created_by = $user->id;
        $sale->assigned_to = $userId;

        // Calculating Lead Data Hash
        $leadHashString = "";
        $leadDatas = $request->lead_data;

        foreach ($leadDatas as $leadData) {
            $leadHashString .= strtolower($leadData['field_value']);
        }

        if($request->has('lead_data')) {
            $individual->lead_data = $request->lead_data;
        }

        $individual->campaign_id = $campaignId;
        $individual->first_action_by = $userId;
        $individual->last_action_by = $userId;
        $lead->company_id = 1;
        $lead->started = 1;
        $lead->lead_status = 1;

        if ($campaign->allow_reference_prefix) {
            $individual->reference_number = $campaign->reference_prefix . Carbon::now()->timestamp;
        }

        $fields = [
            'first_name',
            'last_name',
            'SSN',
            'date_of_birth',
            'phone_number',
            'home_phone',
            'email',
            'language',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $value = $request->$field;
                $individual->$field = in_array($field, ['first_name', 'last_name']) ? ucwords($value) : $value;
            }
        };

        $individual->created_by = $user->id;
        $individual->save();
        $sale->individual_id = $individual->id;
        $lead->individual_id = $individual->id;
        $sale->save();
        $lead->save();

        return ApiResponse::make('Success', [
            'sale_id' => $sale->xid
        ]);
    }

    public function transferSales() {
        $request = request();
        $user = user();
        $success = true;

        if(!$user->hasRole('admin') && !$user->hasPermissionTo('sale_management_view')) {
            throw new ApiException("Not Allowed");
        }

        $userId = $this->getIdFromHash($request->user_id);
        $saleIds = $this->getIdArrayFromHash($request->sales);

        $updatedCount = Sale::whereIn('id', $saleIds)->update(['assigned_to' => $userId]);

        Common::createNotification('sale_transfer', senderId: $user->id, receiverId: $userId, extra: $updatedCount);

        return ApiResponse::make('Success', [
            'success' => $success,
        ]);
    }

    public function export(): BinaryFileResponse
    {
        $request = request();
        $user = user();
        
        $columns = (array) $request->columns;
        $format = $request->format;
        $startDate = "";
        $endDate = "";
        $assignedTo = "";
        $campaignId = "";
        $saleStatusId = "";
        $selectedRowKeys = [];

        if ($request->has('dates') && $request->dates != "") {
            $dates = $request->dates;
            $startDate = $dates[0];
            $endDate = $dates[1];
        }

        if($request->has('assigned_to') && $request->assigned_to != "") {
            $assignedTo = $this->getIdFromHash($request->assigned_to);
        }

        if($request->has('campaign_id') && $request->campaign_id != "") {
            $campaignId = $this->getIdFromHash($request->campaign_id);
        }

        if($request->has('sale_status_id') && $request->sale_status_id != "") {
            $saleStatusId = $this->getIdFromHash($request->sale_status_id);
        }

        if($request->has('selectedRowKeys') && $request->selectedRowKeys != "") {
            $selectedRowKeys = $this->getIdArrayFromHash($request->selectedRowKeys);
        }

        if(!$user->hasRole('admin') && !$user->hasPermissionTo('reports_view')) {
            throw new ApiException("Not Allowed");
        }

        Common::storeIndividualLog(null, 'sales_export');

        return Excel::download(new SalesExport($columns, $startDate, $endDate, $assignedTo, $campaignId, $saleStatusId, $selectedRowKeys), "sales.$format");
    }
}
