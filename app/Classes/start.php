<?php

use App\Models\Company;
use App\Scopes\CompanyScope;

// This is app setting to check if co-applicant is required
if(!function_exists('co_applicant_required')) {
    function co_applicant_required()
    {
        if(env('CO_APPLICANT_REQUIRED')) {
            return env('CO_APPLICANT_REQUIRED');
        } else {
            return false;
        }
    }
}

// Front Landing settings Language Key
if (!function_exists('front_lang_key')) {

    function front_lang_key()
    {
        if (session()->has('front_lang_key')) {
            return session('front_lang_key');
        }

        session(['front_lang_key' => 'en']);
        return session('front_lang_key');
    }
}

// This is app setting for logged in company
if (!function_exists('company')) {

    function company($reset = false)
    {
        if (session()->has('company') && $reset == false) {
            return session('company');
        }

        $company = Company::with(['currency' => function ($query) {
            return $query->withoutGlobalScope(CompanyScope::class);
        }])->first();

        if ($company) {
            session(['company' => $company]);
            return session('company');
        }

        return null;
    }
}

if (!function_exists('super_admin')) {

    /**
     * Return currently logged in user
     */ 
    function super_admin()
    {
        if (session()->has('super_admin')) {
            return session('super_admin');
        }

        $user = auth('api')->user();

        if ($user) {

            session(['super_admin' => $user]);
            return session('super_admin');
        }

        return null;
    }
}

if (!function_exists('user')) {

    /**
     * Return currently logged in user
     */
    function user()
    {
        if (session()->has('user')) {
            return session('user');
        }

        $user = auth('api')->user();

        // TODO - Check if
         if ($user) {
            setPermissionsTeamId($user->company_id);
            $user = $user->load(['roles' => function ($query) use ($user) {
                return $query
                    ->with('permissions');
            }]);

            session(['user' => $user]);
            return session('user');
        }

        return null;
    }
}
