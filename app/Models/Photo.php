<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'source_path', 'thumbnail_path', 'medium_path', 'large_path'];

    public function imageable()
    {
        return $this->morphTo();
    }

    /**
     * A photo belongs to a building
     * @return \App\Models\Building
     */
    public function building()
    {
        return $this->belongsTo('App\Models\Building');
    }
}
