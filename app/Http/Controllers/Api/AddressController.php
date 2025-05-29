<?php

namespace App\Http\Controllers\Api;

use App\Models\State;
use App\Classes\Common;
use App\Models\Address;
use App\Models\Individual;
use App\Models\CoApplicant;
use Examyou\RestAPI\ApiResponse;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Address\StoreRequest;


class AddressController extends ApiBaseController
{
    protected $model = Address::class;

    public function addEdit(StoreRequest $request) {
        $success = true;

        $individualId = $this->getIdFromHash($request->individual_id);
        $individual = Individual::find($individualId);

        if($request->has('address_line1') && $request->address_line1 !== null) {
            $addressFields = [
                'address_line1',
                'address_line2',
                'city',
                'state',
                'zip_code',
            ];

            // Check if address exists or not
            $address = $individual->address ?? new Address;

            foreach ($addressFields as $field) {
                if ($request->has($field)) {
                    $address->$field = in_array($field, ['city', 'address_line1', 'address_line2']) ? ucwords($request->$field) : $request->$field;
                }
            };

            if (!$individual->address_id) {
                $addressNotes = [];

                foreach ($addressFields as $field) {
                    $addressNotes[$field] = in_array($field, ['city', 'address_line1', 'address_line2']) ? ucwords($request->$field) : $request->$field;
                }

                $address->save();

                $individual->address_id = $address->id;
                $individual->save();

                $addressNotes = json_encode($addressNotes);
                Common::storeIndividualLog($individual->id, 'address', $addressNotes);
            } else {
                $updateAddressData = $address->getDirty();

                if($updateAddressData) {
                    $originalAddressData = $address->getOriginal();
                    $changes = [];
        
                    foreach($updateAddressData as $field => $newValue) {
                        $changes[$field] = [
                            'old_value' => $originalAddressData[$field],
                            'new_value' => $newValue,
                        ];
                    }

                    $notes = json_encode($changes);
                    Common::storeIndividualLog($individual->id, 'updated_address', $notes);

                    $address->save();
                }
            }
        }

        if($request->has('co_address_line1') && $request->co_address_line1 !== null && $individual->coApplicant !== null && co_applicant_required()) {
            $coAddressFields = [
                'address_line1' => 'co_address_line1',
                'address_line2' => 'co_address_line2',
                'city' => 'co_city',
                'state' => 'co_state',
                'zip_code' => 'co_zip_code',
            ];

            // Check if co-applicant address exists or not
            $coApplicant = CoApplicant::find($individual->coApplicant->id);

            $co_address = $coApplicant->address ?? new Address;

            foreach ($coAddressFields as $field => $value) {
                if ($request->has($value)) {
                    $co_address->$field = in_array($field, ['city', 'address_line1', 'address_line2']) ? ucwords($request->$value) : $request->$value;
                }
            };

            // Store Co-Applicant Address Log
            if(!$coApplicant->address_id) {
                $coAddressNotes = [];

                foreach ($addressFields as $field) {
                    $coAddressNotes[$field] = in_array($field, ['city', 'address_line1', 'address_line2']) ? ucwords($request->$field) : $request->$field;
                }

                $co_address->save();

                $coApplicant->address_id = $co_address->id;
                $coApplicant->save();

                $coAddressNotes = json_encode($coAddressNotes);
                Common::storeIndividualLog($individual->id, 'co_address', $coAddressNotes);
            } else {
                $updateCoAddressData = $co_address->getDirty();

                if($updateCoAddressData) {
                    $originalCoAddressData = $co_address->getOriginal();

                    foreach($updateCoAddressData as $field => $newValue) {
                        $coChanges[$field] = [
                            'old_value' => $originalCoAddressData[$field],
                            'new_value' => $newValue,
                        ];
                    }

                    $coNotes = json_encode($coChanges);

                    Common::storeIndividualLog($individual->id, 'updated_co_address', $coNotes);

                    $co_address->save();
                }
            }
        }

        return ApiResponse::make('Success', [
            'success' => $success,
        ]);
    }
}