<?php

namespace App\Http\Requests\Api\Address;

use App\Http\Requests\Api\BaseRequest;

class UpdateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address_line1' => 'required|string',
            'address_line2' => 'nullable|string',
            'city' => 'required|string',
            'state_id' => 'required|integer|exists:states,id',
            'zip_code' => 'required|string',
            'co_address_line1' => 'required|string',
            'co_address_line2' => 'nullable|string',
            'co_city' => 'required|string',
            'co_state_id' => 'required|integer|exists:states,id',
            'co_zip_code' => 'required|string',
        ];
    }
}
