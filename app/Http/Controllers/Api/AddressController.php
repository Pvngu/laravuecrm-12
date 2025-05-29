<?php

namespace App\Http\Controllers\Api;

use App\Models\State;
use App\Classes\Common;
use App\Models\Address;
use App\Models\Individual;
use App\Models\CoApplicant;
use Examyou\RestAPI\ApiResponse;
use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Address\IndexRequest;
use App\Http\Requests\Api\Address\StoreRequest;
use App\Http\Requests\Api\Address\UpdateRequest;
use App\Http\Requests\Api\Address\DeleteRequest;


class AddressController extends ApiBaseController
{
    protected $model = Address::class;

    protected $indexRequest = IndexRequest::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
    protected $deleteRequest = DeleteRequest::class;

    public function updated() {
        $request = request();

        if(env('CO_APPLICANT_REQUIRED') === true) $this->addEditCoapplicant($request);
    }

    public function stored(Address $address) {
        $request = request();
        $individualId = $this->getIdFromHash($request->individual_id);
        $individual = Individual::find($individualId);
        $individual->address_id = $address->id;
        $individual->save();

        if(env('CO_APPLICANT_REQUIRED') === true) $this->addEditCoapplicant($request);
    }

    public function addEditCoapplicant($request) {
        $individualId = $this->getIdFromHash($request->individual_id);
        $individual = Individual::find($individualId);

        if ($individual->coApplicant) {
            $coApplicant = CoApplicant::find($individual->coApplicant->id);
        } else {
            $coApplicant = new CoApplicant;
            $coApplicant->individual_id = $individualId;
        }

        if ($request->has('co_address_line1') && $request->co_address_line1 !== null) {
            $coAddressFields = [
                'address_line1' => 'co_address_line1',
                'address_line2' => 'co_address_line2',
                'city' => 'co_city',
                'state' => 'co_state',
                'zip_code' => 'co_zip_code',
            ];

            $co_address = $coApplicant->address ?? new Address;

            foreach ($coAddressFields as $field => $value) {
                if ($request->has($value)) {
                    $co_address->$field = $request->$value;
                }
            }

            if (!$coApplicant->address_id) {
                $co_address->save();
                $coApplicant->address_id = $co_address->id;
                $coApplicant->save();
            }
        }
    }
}