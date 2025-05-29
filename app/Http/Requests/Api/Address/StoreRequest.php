<?php

namespace App\Http\Requests\Api\Address;

use Illuminate\Validation\Rule;
use App\Http\Requests\Api\BaseRequest;

class StoreRequest extends BaseRequest
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
            'address_line1' => [
                'nullable',
                'string',
                'required_with:address_line2,city,state_id,zip_code',
            ],
            'address_line2' => ['nullable','string'],
            'city' => [
                'nullable',
                'string',
                'required_with:address_line1,address_line2,state_id,zip_code',
            ],
            'state_id' => [
                'nullable',
                'integer',
                'exists:states,id',
                'required_with:address_line1,address_line2,city,zip_code',
            ],
            'zip_code' => [
                'nullable',
                'string',
                'required_with:address_line1,address_line2,city,state_id',
            ],
            'co_address_line1' => [
                'nullable',
                'string',
                'required_with:co_address_line2,co_city,co_state_id,co_zip_code',
            ],
            'co_address_line2' => ['nullable','string'],
            'co_city' => [
                'nullable',
                'string',
                'required_with:co_address_line1,co_address_line2,co_state_id,co_zip_code',
            ],
            'co_state_id' => [
                'nullable',
                'string',
                'exists:states,id',
                'required_with:co_address_line1,co_address_line2,co_city,co_zip_code',
            ],
            'co_zip_code' => [
                'nullable',
                'string',
                'required_with:co_address_line1,co_address_line2,co_city,co_state_id',
            ],
            // 'address_check' => [
            //     Rule::requiredIf(function () {
            //         return $this->allAddressFieldsAreNull();
            //     }),
            // ],
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'address_line1.required_with' => 'The address line1 field is required.',
            'city.required_with' => 'The city field is required.',
            'state_id.required_with' => 'The state field is required.',
            'zip_code.required_with' => 'The zip code field is required.',
            'co_address_line1.required_with' => 'The address line1 field is required.',
            'co_city.required_with' => 'The city field is required.',
            'co_state_id.required_with' => 'The state field is required.',
            'co_zip_code.required_with' => 'The zip code field is required.',
        ];
    }

    protected function allAddressFieldsAreNull()
{
    $addressFields = [
        'address_line1',
        'address_line2',
        'city',
        'state_id',
        'zip_code',
        'co_address_line1',
        'co_address_line2',
        'co_city',
        'co_state_id',
        'co_zip_code',
    ];

    foreach ($addressFields as $field) {
        if (!is_null($this->input($field))) {
            return false;
        }
    }

    return true;
}
}
