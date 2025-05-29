<?php

namespace App\Http\Requests\Api\ActivityLog;

use App\Http\Requests\Api\BaseRequest;

class IndexRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'entity' => 'nullable|string|max:255',
            'action' => 'nullable|string|in:CREATED,UPDATED,DELETED',
            'user_id' => 'nullable|string',
            'model_type' => 'nullable|string',
            'model_id' => 'nullable|integer',
            'dates' => 'nullable|string',
        ];
    }
}
