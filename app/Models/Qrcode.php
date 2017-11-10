<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Llama\Database\Eloquent\ModelTrait;
use HipsterJazzbo\Landlord\BelongsToTenants;

class Qrcode extends Model
{
    const EXPIRATION_DAYS = 30;

    use ModelTrait;
    use BelongsToTenants;

    public $tenantColumns = [MANUFACTURER_COMPANY_ID];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'expire_on'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'building_id',
        'identifier',
        'path',
        'expire_on',
        'created_by',
        'type',
    ];

    /**
     * The attributes that are visible.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at',
    ];

    protected $appends = [
        'public_path',
    ];

    /**
     * An qr code belongs to company
     * @return \App\Models\Company
     */
    public function manufacturer()
    {
        return $this->belongsTo(Company::class, MANUFACTURER_COMPANY_ID);
    }

    /**
     * A qrcode belongs to a Building
     * @return \App\Models\Building
     */
    public function qrcode()
    {
        return $this->belongsTo('App\Models\Building');
    }

    /**
     * @return mixed
     */
    public function getPublicPathAttribute()
    {
        return \Storage::url($this->path);
    }

    /**
     * @param $query
     * @param $building_id
     * @param $type
     * @return mixed
     */
    public function scopeGetQrCode($query, $building_id, $type)
    {
        return $query->where('building_id', $building_id)->where('type', $type);
    }

    /**
     * @param $query
     * @param $identifier
     * @return mixed
     */
    public function scopeGetByIdentifier($query, $identifier)
    {
        return $query->where('identifier', $identifier);
    }


    /**
     * A QrCode belongs to a building
     * @return \App\Models\Building
     */
    public function building()
    {
        return $this->hasOne('\App\Models\Building', 'id', 'building_id');
    }

    /**
     * A QrCode belongs to a files through building id
     * @return \App\Models\File
     */
    public function files()
    {
        return $this->hasMany('\App\Models\File', 'storable_id', 'building_id')->where('category_id', 'build_status');
    }

}
