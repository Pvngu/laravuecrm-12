<?php

namespace App\Http\Controllers;

use Examyou\RestAPI\ApiController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Classes\Common;
use App\Models\Company;
use App\Models\Settings;

class ApiBaseController extends ApiController
{
    use AuthorizesRequests, DispatchesJobs;

    public $company = [];

    public function __construct()
    {
        parent::__construct();

        // $this->company = Company::first();

        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    public function getIdFromHash($hash)
    {
        return Common::getIdFromHash($hash);
    }

    public function getHashFromId($id)
    {
        return Common::getHashFromId($id);
    }

    public function getIdArrayFromHash($values)
    {
        $convertedArray = [];

        foreach ($values as $value) {
            $convertedArray[] = $this->getIdFromHash($value);
        }

        return $convertedArray;
    }

    public function getHashArrayFromId($values)
    {
        $convertedArray = [];

        foreach ($values as $value) {
            $convertedArray[] = $this->getHashFromId($value);
        }

        return $convertedArray;
    }

    public function errorMessageResponse($message)
    {
        return response()->json([
            'code' => 403,
            'message' => $message
        ], 403);
    }
}
