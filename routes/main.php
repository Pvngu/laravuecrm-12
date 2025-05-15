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
    $lang = $company && $company->lang_id && $company->lang_id != null ? Lang::find($company->lang_id) : Lang::first();

    return view('welcome', [
        'appName' => $appName,
        'appVersion' => preg_replace("/\r|\n/", "", $appVersion),
        'themeMode' => $themeMode,
        'company' => $company,
        'appEnv' => env('APP_ENV'),
        'appType' => 'non-saas',
        'loadingImage' => $company->light_logo_url,
    ]);
})->where('path', '^(?!api.*$).*')->name('main');
