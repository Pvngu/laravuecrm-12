<?php

namespace App\Models;

use App\Casts\Hash;
use App\Models\BaseModel;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityLog extends BaseModel
{
    use HasFactory;

    protected $table = 'activity_logs';

    protected $hidden = ['id', 'company_id', 'user_id', 'loggable_id'];

    protected $appends = ['xid', 'x_company_id', 'x_user_id'];

    protected $filterable = ['action', 'entity', 'user_id', 'loggable_type'];

    protected $hashableGetterFunctions = [
        'getXCompanyIdAttribute' => 'company_id',
        'getXUserIdAttribute' => 'user_id',
    ];

    protected $casts = [
        'company_id' => Hash::class . ':hash',
        'user_id' => Hash::class . ':hash',
        'json_log' => 'array',
        'datetime' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function loggable()
    {
        return $this->morphTo();
    }

    /**
     * Scope to filter by specific model
     */
    public function scopeForModel($query, $model)
    {
        return $query->where('loggable_type', get_class($model))
                    ->where('loggable_id', $model->id);
    }

    /**
     * Scope to filter by action
     */
    public function scopeForAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope to filter by entity
     */
    public function scopeForEntity($query, $entity)
    {
        return $query->where('entity', $entity);
    }
}
