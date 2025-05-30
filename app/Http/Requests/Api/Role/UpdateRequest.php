<?php

namespace App\Http\Requests\Api\Role;

use Vinkla\Hashids\Facades\Hashids;;
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
		$convertedId = Hashids::decode($this->route('role'));
		$id = $convertedId[0];

		return [
			'name' => 'required|unique:roles,name,' . $id,
			'permissions' => 'required'
		];
	}
}
