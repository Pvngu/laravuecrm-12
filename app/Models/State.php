<?php

namespace App\Models;

use App\Models\BaseModel;

class State extends BaseModel
{
    protected $table = 'states';

    protected $default = [
        'id',
        'name',
        'code',
    ];

    protected $appends = ['xid'];

    protected static function boot()
    {
        parent::boot();
    }
}
