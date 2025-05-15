<?php

namespace App\Observers;

use App\Classes\Common;
use App\Models\Company;
use Spatie\Permission\Models\Role;

class CompanyObserver
{

    public function created(Company $company)
    {
        // $company = Common::addCurrencies($company);

        if (!$company->is_global) {
            $company = $this->addAdminRole($company);
            Common::insertInitSettings($company);
        }
    }

    public function addAdminRole($company)
    {   
        // Seeding Data
        Role::create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Admin is allowed to manage everything of the app.',
        ]);

        return $company;
    }
}
