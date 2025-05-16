<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Casts\Hash;
use App\Traits\LogsActivity;

class Note extends BaseModel
{
    use LogsActivity;

    protected $table = 'notes';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['id', 'noteable_type', 'noteable_id', 'created_by_id', 'updated_by_id'];

    protected $appends = ['xid', 'x_created_by_id', 'x_updated_by_id'];

    protected $filterable = ['noteable_type', 'created_by_id'];

    protected $hashableGetterFunctions = [
        'getXCreatedByIdAttribute' => 'created_by_id',
        'getXUpdatedByIdAttribute' => 'updated_by_id',
    ];

    protected $casts = [
        'created_by_id' => Hash::class . ':hash',
        'updated_by_id' => Hash::class . ':hash',
    ];

    protected static function boot()
    {
        parent::boot();
    }

    public function noteable()
    {
        return $this->morphTo();
    }

    public function individual()
    {
        return $this->belongsTo(Individual::class, 'noteable_id');
    }

    public function debt()
    {
        return $this->belongsTo(Debt::class, 'noteable_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }

    public function getExcludedLogFields()
    {
        return ['noteable_type', 'noteable_id', 'alert_type', 'created_by_id', 'updated_by_id'];
    }
}