<?php

namespace App\Http\Requests\Api\Individual;

use App\Http\Requests\Api\BaseRequest;

class StoreRequest extends BaseRequest
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
            'phone_number' => ['required', 'regex:/^\d{10}$/'],
            'home_phone' => ['nullable', 'regex:/^\d{10}$/'],
            'email' => 'nullable|email',
        ];
    }
}
