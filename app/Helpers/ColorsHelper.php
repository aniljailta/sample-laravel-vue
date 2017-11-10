<?php

if ( ! function_exists('get_color')) {
    function get_color($category, $options) {
        
        
        
        
        $rColor = null;
        if ($color === null) return $rColor;
        if (is_array($color))
        {
            $colorID = $color['id'];
            $colorName = $color['name'];
        } else
        if (is_string($color) || is_numeric($color))
        {
            $colorID = $colorName = $color;
        }

        if (isset($colors[$colorID]))
        {
            $rColor = $colors[$colorID];

            if ($rColor['type'] === 'custom') {
                $rColor['name'] = ($colorName !== '') ? $colorName : $rColor['type'];
            }
        }

        return $rColor;
        
    }
}