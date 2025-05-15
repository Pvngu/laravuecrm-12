<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Scopes\CompanyScope;

class SaleStatus extends BaseModel
{
    protected $table = 'sale_statuses';

    protected $default = ['xid', 'name'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid'];

    protected $filterable = ['name'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }

    public function lead()
    {
        return $this->hasOne(Lead::class);
    }
}
