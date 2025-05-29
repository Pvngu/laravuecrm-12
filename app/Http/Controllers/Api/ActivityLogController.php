<?php

namespace App\Http\Controllers\Api;

use App\Models\ActivityLog;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\ActivityLog\IndexRequest;

class ActivityLogController extends ApiBaseController
{   
    protected $model = ActivityLog::class;
    protected $indexRequest = IndexRequest::class;

    public function modifyIndex($query) {
        $request = request();

        // Filter by entity if provided
        if ($request->has('entity') && $request->entity != "") {
            $query = $query->where('entity', $request->entity);
        }

        // Filter by action if provided
        if ($request->has('action') && $request->action != "") {
            $query = $query->where('action', $request->action);
        }

        // Filter by user if provided
        if ($request->has('user_id') && $request->user_id != "") {
            $userId = $this->getIdFromHash($request->user_id);
            $query = $query->where('user_id', $userId);
        }

        // Filter by model type if provided
        if ($request->has('model_type') && $request->model_type != "") {
            $query = $query->where('loggable_type', $request->model_type);
        }

        // Filter by model ID if provided
        if ($request->has('model_id') && $request->model_id != "") {
            $query = $query->where('loggable_id', $request->model_id);
        }

        // Dates Filters
        if ($request->has('dates') && $request->dates != "") {
            $dates = explode(',', $request->dates);
            $startDate = $dates[0];
            $endDate = $dates[1];

            $query = $query->whereRaw('activity_logs.datetime >= ?', [$startDate])
                ->whereRaw('activity_logs.datetime <= ?', [$endDate]);
        }

        // Default order by datetime descending
        $query = $query->orderBy('datetime', 'desc');

        return $query;
    }
}
