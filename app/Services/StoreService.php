<?php

namespace App\Services;

class StoreService
{

    /**
     * List of loaded helpers
     *
     * @var array
     * @access protected
     */
    protected static $store = [];

    public static function set($key, $data)
    {
        self::$store[$key] = $data;
    }

    public static function get($key)
    {
        if(isset(self::$store[$key]))
            return self::$store[$key];
        
        return null;
    }

}
