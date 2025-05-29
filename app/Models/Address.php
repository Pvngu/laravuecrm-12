<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Address extends BaseModel
{
    use HasFactory;

    protected $appends = ['xid', 'full_address'];

    protected $hidden = ['id'];
    
    protected $default = ['id','xid','address_line1','address_line2','city','state','zip_code','full_address'];

    protected $fillable = ['address_line1','address_line2','city','state','zip_code',];

    protected static function boot()
    {
        parent::boot();
    }

    public function individual()
    {
        return $this->hasOne(Individual::class);
    }

    public function coApplicant()
    {
        return $this->hasOne(CoApplicant::class);
    }

    public function state() {
        return $this->belongsTo(State::class);
    }

    public function getFullAddressAttribute()
    {
        return $this->address_line1 . ' ' . $this->city . ', ' . $this->state . ' ' . $this->zip_code;
    }
}
