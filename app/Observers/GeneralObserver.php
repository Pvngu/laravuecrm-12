<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
class GeneralObserver
{
    public function saving(Model $model)
    {
        $company = company();

        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if ($company && !$company->is_global) {
            $model->company_id = $company->id;
        }
    }
}
