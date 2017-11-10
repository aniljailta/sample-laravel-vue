<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileSign extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'file_signs';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [ 'created_at', 'updated_at', 'deleted_at' ];

    protected $casts = [
        'is_esigned' => 'boolean'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
        'is_esigned',
        'signer_role',
        'signer_id',
        'esigned_on',
        'esign_signature_id',
        'esign_signature_request_id',
        'esign_user_agent',
        'esign_ip_address',
    ];

    public static $rules = [
        'id' => ['numeric'],
        'is_esigned' => ['in:1,0'],
        'signer_role' => ['string', 'nullable'],
        'signer_id' => ['numeric', 'nullable'],
        'esigned_on' => ['date_format:Y-m-d H:i:s'],
        'esign_signature_id' => ['string'],
        'esign_signature_request_id' => ['string'],
        'esign_user_agent' => ['string'],
        'esign_ip_address' => ['string']
    ];

    const CUSTOMER_ROLE = 'customer';
    
    /**
     * A file sign belongs to a file
     * @return \App\Models\File
     */
    public function file()
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }

    /**
     * A file sign is morpable by signers
     */
    public function signer()
    {
        return $this->morphTo('signer', 'role', 'id');
    }

    /**
     * A file sign belongs to a company
     * @return \App\Models\RtoCompany
     */
    public function rto_company()
    {
        return $this->belongsTo(RtoCompany::class, 'signer_id');
    }

    /**
     * A file sign belongs to a customer TODO: customer model
     * @return \App\Models\OrderReference
     */
    public function customer()
    {
        return $this->belongsTo(OrderReference::class, 'signer_id');
    }
}
