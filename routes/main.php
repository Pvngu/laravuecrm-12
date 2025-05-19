<?php

use App\Models\Company;
use App\Models\Lang;
use App\Models\Translation;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

Route::get('{path}', function () {
    $appName = "LeadPro";
    $appVersion = File::get(public_path() . '/version.txt');
    $themeMode = session()->has('theme_mode') ? session('theme_mode') : 'light';
    $company = Company::first();
    $appVersion = File::get('version.txt');
    $appVersion = preg_replace("/\r|\n/", "", $appVersion);

    return view('welcome', [
        'appName' => $appName,
        'appVersion' => preg_replace("/\r|\n/", "", $appVersion),
        'themeMode' => $themeMode,
        'company' => $company,
        'appEnv' => env('APP_ENV'),
        'appType' => 'non-saas',
        'loadingImage' => $company->light_logo_url,
        'coApplicantRequired' => env('CO_APPLICANT_REQUIRED')
    ]);
})->where('path', '^(?!api.*$).*')->name('main');
