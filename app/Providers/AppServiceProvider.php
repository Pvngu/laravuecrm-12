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
use App\Models\Document;
use App\Models\Settings;
use App\Models\Individual;
use App\Models\EmailTemplate;
use App\Models\FormFieldName;
use App\Models\IndividualLog;
use App\Models\ExpenseCategory;
use App\Observers\FormObserver;
use App\Observers\UserObserver;
use App\Observers\CompanyObserver;
use App\Observers\GeneralObserver;
use App\Observers\LeadLogObserver;
use App\Observers\DocumentObserver;
use App\Observers\IndividualObserver;
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
        LeadLog::observe(LeadLogObserver::class);
        Document::observe(DocumentObserver::class);
        Individual::observe(IndividualObserver::class);
        
        // Add company scope to models
        foreach ([
            Campaign::class, 
            Currency::class, 
            Settings::class, 
            Form::class,
            FormFieldName::class, 
            EmailTemplate::class, 
            Expense::class,
            ExpenseCategory::class,
            Product::class,
            Lead::class,
            IndividualLog::class,
            Document::class,
        ] as $model) {
            $model::observe(GeneralObserver::class);
        }
    }
}
