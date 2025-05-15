<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiBaseController;
use App\Models\State;

class StateController extends ApiBaseController
{
    protected $model = State::class;

    public function allStates()
    {
        $states = cache()->remember('states', 60 * 60 * 24, function () {
            return State::select('id', 'name', 'code')->get();
        });

        return response()->json([
            'data' => $states,
        ]);
    }
}
