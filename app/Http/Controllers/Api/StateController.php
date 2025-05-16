<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Models\State;
use App\Traits\SelectOptionsTraits;

class StateController extends ApiBaseController
{
    use SelectOptionsTraits;
    protected $model = State::class;
}
