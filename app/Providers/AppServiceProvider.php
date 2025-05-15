<?php

namespace App\Providers;

use App\Models\Form;
use App\Models\Role;
use App\Models\User;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Settings;
use App\Models\EmailTemplate;
use App\Models\FormFieldName;
use App\Observers\FormObserver;
use App\Observers\RoleObserver;
use App\Observers\UserObserver;
use App\Observers\SettingObserver;
use App\Observers\CurrencyObserver;
use Illuminate\Support\ServiceProvider;
use App\Observers\EmailTemplateObserver;
use App\Observers\FormFieldNameObserver;
use App\Observers\CompanyObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Company::observe(CompanyObserver::class);
        User::observe(UserObserver::class);
        Settings::observe(SettingObserver::class);
        Currency::observe(CurrencyObserver::class);
        EmailTemplate::observe(EmailTemplateObserver::class);
        Form::observe(FormObserver::class);
        FormFieldName::observe(FormFieldNameObserver::class);
    }
}
