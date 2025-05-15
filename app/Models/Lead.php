<?php

namespace App\Models;

use App\Casts\Hash;
use App\Models\BaseModel;
use App\Scopes\CompanyScope;
use App\Events\LeadStatusChanged;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lead extends BaseModel
{
    use HasFactory;

    protected $table = 'leads';

    protected $default = ['xid', 'reference_number', 'language'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['id', 'company_id'];

    protected $appends = ['xid', 'x_company_id'];

    protected $filterable = ['reference_number','first_name','last_name', 'campaign_id', 'individual_status'];

    protected $hashableGetterFunctions = [
        'getXCompanyIdAttribute' => 'company_id',
    ];

    protected $casts = [
        'company_id' => Hash::class . ':hash',
        'lead_data' => 'array',
        'time_taken' => 'integer',
        'started' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);

        static::updated(function ($lead) { event(new LeadStatusChanged($lead)); });
    }

    public function leadStatus()
    {
        return $this->belongsTo(LeadStatus::class, 'lead_status');
    }

    public function individual()
    {
        return $this->belongsTo(Individual::class);
    }
}
