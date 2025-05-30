<?php

namespace App\Scopes;

use App\Classes\Common;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Schema;

class CompanyScope implements Scope
{
    public function apply(Builder $builder, Model $model)
    {

        // When user is logged in
        // auth('api')->user() do not work in apply so we have use auth()->check()
        if (auth('api')->check() && Schema::hasColumn($model->getTable(), 'company_id')) {
            $company = company();

            $builder->where($model->getTable() . '.company_id', '=', Common::getIdFromHash($company->xid));
        }
    }
}
