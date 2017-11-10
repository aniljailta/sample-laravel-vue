<?php

if ( ! function_exists('date_from_custom_datepicker')) {
    function date_from_custom_datepicker($date)
    {
        list($d, $M, $y, $h, $m, $s) = sscanf($date, "%2d %3s, %4d %2d:%2d:%2d");
        $h = str_pad($h, 2, '0', STR_PAD_LEFT);
        $m = str_pad($m, 2, '0', STR_PAD_LEFT);
        $s = str_pad($s, 2, '0', STR_PAD_LEFT);

        return strtotime("$d $M $y $h:$m:$s");
    }
}