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

    protected $default = ['id','xid','reference_number','first_name','last_name', 'full_name','SSN','date_of_birth','home_phone','phone_number','email','language','lead_data','time_taken','template_form','full_address','last_action_by','x_last_action_by','x_campaign_id'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $hidden = ['id', 'company_id', 'campaign_id', 'first_action_by', 'last_action_by', 'individual_follow_up_id', 'salesman_booking_id'];

    protected $appends = ['xid', 'x_company_id', 'x_campaign_id', 'x_first_action_by', 'x_last_action_by', 'x_individual_follow_up_id', 'x_salesman_booking_id', 'full_name', 'template_form', 'full_address'];

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
        'date_of_birth' => 'date',
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

    public function document()
    {
        return $this->hasMany(Document::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function coApplicant()
    {
        return $this->hasOne(CoApplicant::class);
    }

    public function notes()
    {
        return $this->morphMany(Note::class, 'noteable');
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFullAddressAttribute()
    {
        if ($this->address) {
            $address = $this->address;
            $parts = [
                $address->address_line1,
                $address->city,
                // optional($address->state)->name,
                $address->zip_code,
                optional($address->state)->code,
            ];
            return implode(', ', array_filter($parts));
        }
        return '';
    }

    public function getTemplateFormAttribute()
    {
        $fields = [
            'first_name',
            'last_name',
            'SSN',
            'date_of_birth',
            'phone_number',
            'home_phone',
            'email',
            'language',
        ];

        $template = [];
        foreach ($fields as $field) {
            $template[] = [
            'field_name' => $field,
            'field_value' => $this->$field,
            ];
        }

        $coFields = [
            'first_name',
            'last_name',
            'SSN',
            'date_of_birth',
            'phone_number',
            'home_phone',
            'email',
            'language',
        ];

        foreach ($coFields as $coField) {
            $template[] = [
            'field_name' => 'co_' . $coField,
            'field_value' => $this->coApplicant ? $this->coApplicant->$coField : '',
            ];
        }

        if (is_array($this->lead_data)) {
            foreach ($this->lead_data as $leadField) {
                $template[] = [
                    'field_name' => $leadField['field_name'] ?? '',
                    'field_value' => $leadField['field_value'] ?? '',
                ];
            }
        }

        return $template;
    }

    public function getPhoneNumberAttribute($value)
    {
        // Format: +52 664 345 7777
        if (!$value) return $value;
        $value = preg_replace('/[^\d+]/', '', $value); // Remove non-numeric except +
        if (preg_match('/^(\+\d{2})(\d{3})(\d{3})(\d{4})$/', $value, $matches)) {
            return $matches[1] . ' ' . $matches[2] . ' ' . $matches[3] . ' ' . $matches[4];
        }
        return $value;
    }

    public function setPhoneNumberAttribute($value)
    {
        // Store as: +526643457777
        if ($value) {
            $this->attributes['phone_number'] = preg_replace('/\s+/', '', $value);
        } else {
            $this->attributes['phone_number'] = $value;
        }
    }
}
