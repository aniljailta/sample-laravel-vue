<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;
use Closure;
use Request;

class ConvertStringToTypes extends TransformsRequest
{
    /**
     * Transform the given value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        /*
         * Notes
         * (int), (integer) - cast to integer
         * (bool), (boolean) - cast to boolean
         * (float), (double), (real) - cast to float
         * (string) - cast to string
         * (array) - cast to array
         * (object) - cast to object
         * (unset) - cast to NULL (PHP 5)
         */

        if (is_string($value)) {
            $transformedValue = strtolower($value);

            // return null
            if ($transformedValue === 'null' || $transformedValue === '') {
                return null;
            }

            // return boolean
            if (in_array($transformedValue, array('true', 'false'), true)) {
                return ($transformedValue === 'true' ? true : false);
            }

            // return int or float
            /* if (is_numeric($transformedValue)) {
                if (is_float($transformedValue)) return (float) $transformedValue;
                return (int) $transformedValue;
            }
            */
        }

        // return value as is
        return $value;
    }
}
