<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Casts\Hash;
use App\Models\BaseModel;
use App\Scopes\CompanyScope;

class Individual extends BaseModel
{
    use HasFactory;
    
    protected $table = 'individuals';

    protected $default = ['xid'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['id', 'company_id', 'campaign_id', 'first_action_by', 'last_action_by', 'individual_follow_up_id', 'salesman_booking_id'];

    protected $appends = ['xid', 'x_company_id', 'x_campaign_id', 'x_first_action_by', 'x_last_action_by', 'x_individual_follow_up_id', 'x_salesman_booking_id', 'full_name'];

    protected $filterable = ['reference_number','first_name','last_name', 'campaign_id', 'individual_status'];

    protected $hashableGetterFunctions = [
        'getXCompanyIdAttribute' => 'company_id',
        'getXCampaignIdAttribute' => 'campaign_id',
        'getXFirstActionByAttribute' => 'first_action_by',
        'getXLastActionByAttribute' => 'last_action_by',
        'getXIndividualFollowUpIdAttribute' => 'individual_follow_up_id',
        'getXSalesmanBookingIdAttribute' => 'salesman_booking_id',
    ];

    protected $casts = [
        'company_id' => Hash::class . ':hash',
        'campaign_id' => Hash::class . ':hash',
        'first_action_by' => Hash::class . ':hash',
        'last_action_by' => Hash::class . ':hash',
        'individual_follow_up_id' => Hash::class . ':hash',
        'salesman_booking_id' => Hash::class . ':hash',
        'lead_data' => 'array',
        'time_taken' => 'integer',
        'started' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new CompanyScope);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function firstActioner()
    {
        return $this->belongsTo(User::class, 'first_action_by', 'id');
    }

    public function lastActioner()
    {
        return $this->belongsTo(User::class, 'last_action_by', 'id');
    }

    public function individualFollowUp()
    {
        return $this->belongsTo(IndividualLog::class, 'individual_follow_up_id', 'id');
    }

    public function salesmanBooking()
    {
        return $this->belongsTo(IndividualLog::class, 'salesman_booking_id', 'id');
    }

    public function lead()
    {
        return $this->hasOne(Lead::class);
    }

    public function sale()
    {
        return $this->hasOne(Sale::class);
    }

    public function bankAccount()
    {
        return $this->hasOne(BankAccount::class);
    }

    public function creditCard()
    {
        return $this->hasOne(CreditCard::class);
    }

    public function document()
    {
        return $this->hasMany(Document::class);
    }

    public function smsMessages()
    {
        return $this->hasMany(SmsMessages::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function coApplicant()
    {
        return $this->hasOne(CoApplicant::class);
    }

    public function debts()
    {
        return $this->hasMany(Debt::class);
    }

    public function conversation()
    {
        return $this->hasOne(IndividualConversation::class);
    }

    public function enrollment()
    {
        return $this->hasOne(Enrollment::class);
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Accessors & Mutators
}
