<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Http\Requests\Api\Individual\IndexRequest;
use App\Http\Requests\Api\Individual\StoreRequest;
use App\Http\Requests\Api\Individual\UpdateRequest;
use App\Http\Requests\Api\Individual\DeleteRequest;
use App\Models\Individual;

class IndividualController extends ApiBaseController
{
    protected $model = Individual::class;
}
