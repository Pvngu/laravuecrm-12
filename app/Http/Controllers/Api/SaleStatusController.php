<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\SaleStatus\StoreRequest;
use App\Http\Requests\Api\SaleStatus\UpdateRequest;
use App\Models\SaleStatus;

class SaleStatusController extends ApiBaseController
{
    protected $model = SaleStatus::class;
    protected $storeRequest = StoreRequest::class;
    protected $updateRequest = UpdateRequest::class;
  
}
