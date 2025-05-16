<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Scopes\CompanyScope;

class CoApplicant extends BaseModel
{
    protected $hidden = ['id'];

    protected $default = [
        'id',
        'xid',
        'first_name',
        'last_name',
        'SSN',
        'date_of_birth',
        'home_phone',
        'phone_number',
        'email',
        'language',
    ];

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