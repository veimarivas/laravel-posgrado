<?php

namespace App\Helpers;

class ViewHelper
{
    public static function hexToRgb($hex, $alpha = 0.08)
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        $rgb = sscanf($hex, '%02x%02x%02x');
        return "rgba({$rgb[0]}, {$rgb[1]}, {$rgb[2]}, {$alpha})";
    }
}
