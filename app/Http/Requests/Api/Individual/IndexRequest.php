<?php

namespace App\Http\Requests\Api\Individual;

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
            'x_sale_lead_id' => 'required',
            'first_name' => 'nullable',
            'last_name' => 'nullable',
            'SSN' => 'nullable',
            'date_of_birth' => 'nullable|date',
            'home_phone' => ['nullable', 'regex:/^\+\d{1,3}\s\d{1,4}\s\d{3}\s\d{4}$/'],
            'phone_number' => ['required', 'regex:/^\+\d{1,3}\s\d{1,4}\s\d{3}\s\d{4}$/'],
            'email' => 'nullable|email',
        ];
    }
}
