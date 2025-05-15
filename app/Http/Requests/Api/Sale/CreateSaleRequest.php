<?php

namespace App\Http\Requests\Api\Sale;

use App\Http\Requests\Api\BaseRequest;

class CreateSaleRequest extends BaseRequest
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
        $rules = [
            'campaign_id' => 'required',
            'assigned_user_id' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ];

        // TODO - condition according to required Type


        return $rules;
    }
}
