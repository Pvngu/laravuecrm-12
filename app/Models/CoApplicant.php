<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Scopes\CompanyScope;

class CoApplicant extends BaseModel
{
    protected $hidden = ['id'];

    protected $appends = ['xid'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }

    public function individual()
    {
        return $this->belongsTo(Individual::class);
    }

    public function address() {
        return $this->belongsTo(Address::class);
    }

}