<?php
namespace App\Models;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    /**
     * The attributes that are visible.
     *
     * @var array
     */
    protected $visible = [
        'description',
        'display_name',
        'name'
    ];
}