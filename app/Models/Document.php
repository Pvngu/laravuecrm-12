<?php

namespace App\Models;

use App\Casts\Hash;
use App\Classes\Common;
use App\Scopes\CompanyScope;
use App\Models\BaseModel;

class Document extends BaseModel
{
    protected $table = 'documents';
    
    protected $default = ["xid", "name", "file_path", "file_type", "file_size", "created_by_id", "updated_by_id"];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $appends = ['xid', 'x_individual_id', 'x_created_by_id', 'file_url'];

    protected $filterable = ['individual_id',];

    protected $hashableGetterFunctions = [
        'getXIndividualIdAttribute' => 'individual_id',
        'getXCreatedByIdAttribute' => 'created_by_id',
    ];

    protected $casts = [
        'individual_id' => Hash::class . ':hash',
        'user_id' => Hash::class . ':hash',
        'created_by_id' => Hash::class . ':hash',
    ];

    protected static function boot()
    {
        parent::boot();

        // static::addGlobalScope(new CompanyScope);
    }

    public function getFileUrlAttribute()
    {
        $docFilesPath = Common::getFolderPath('docFilesPath');

        return $this->file == null ? null : Common::getFileUrl($docFilesPath, $this->file);
    }

    public function individual() {
        return $this->belongsTo(Individual::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_id', 'id')->withoutGlobalScopes();
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by_id', 'id')->withoutGlobalScopes();
    }
}
