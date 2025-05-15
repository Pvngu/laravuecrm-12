<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends BaseModel
{

    use HasFactory;

    protected $table = 'sales';

    protected $default = ['xid'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['id', 'assigned_to', 'individual_id', 'company_id'];

    protected $appends = ['xid'];

    protected $filterable = ['assigned_to', 'campaign_id', 'sale_status_id', 'reference_number','first_name', 'last_name', 'home_phone', 'phone_number','email', 'SSN'];

    protected $hashableGetterFunctions = [
        'getXAssignedToAttribute' => 'assigned_to',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);

        static::deleting(function ($sale) {
            $sale->individual()->delete();
        });
    }

    public function individual()
    {
        return $this->belongsTo(Individual::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function saleStatus()
    {
        return $this->belongsTo(SaleStatus::class);
    }
}
