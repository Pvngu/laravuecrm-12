<?php

namespace App\Providers;

use App\Models\Form;
use App\Models\Lead;
use App\Models\User;
use App\Models\Company;
use App\Models\Expense;
use App\Models\LeadLog;
use App\Models\Product;
use App\Models\Campaign;
use App\Models\Currency;
use App\Models\Settings;
use App\Models\EmailTemplate;
use App\Models\FormFieldName;
use App\Models\ExpenseCategory;
use App\Observers\FormObserver;
use App\Observers\UserObserver;
use App\Observers\CompanyObserver;
use App\Observers\GeneralObserver;
use App\Observers\LeadLogObserver;
use Illuminate\Support\ServiceProvider;

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
        Form::observe(FormObserver::class);
        LeadLog::observe(LeadLogObserver::class);
        
        // Add company scope to models
        foreach ([
            Campaign::class, 
            Currency::class, 
            Settings::class, 
            FormFieldName::class, 
            EmailTemplate::class, 
            Form::class,
            Expense::class,
            ExpenseCategory::class,
            Product::class,
            Lead::class
        ] as $model) {
            $model::observe(GeneralObserver::class);
        }
    }
}
