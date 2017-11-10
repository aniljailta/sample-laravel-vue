<?php

if ( ! function_exists('array_group_by')) {
    function array_group_by($array, $key) {
        $return = array();
        foreach($array as $val) {
            $return[$val[$key]] = $val;
        }
        return $return;
    }
}