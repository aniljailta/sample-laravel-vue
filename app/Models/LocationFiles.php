<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Llama\Database\Eloquent\ModelTrait;

class LocationFiles extends Model
{
    use ModelTrait;


    protected $table = 'location_files';

    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_id',
        'verified_lat',
        'verified_long'
        ];

    /**
     * The attributes that are visible.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at'
    ];

    public static $validator = 'App\Validators\LocationValidator';
    
    public static $rules = [
        'verified_lat' => ['string', 'max:255'],
        'verified_long' => ['string', 'max:255']
    ];

    /**
     * A  location file  belongs to a file
     * @return \App\Models\File
     */
    public function file()
    {
        return $this->belongsTo(File::class, 'file_id', 'id');
    }

    
}
