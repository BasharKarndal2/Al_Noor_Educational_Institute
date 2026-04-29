<?php

namespace App\Helpers;

class ColorHelper
{
    public static function getContrastColor($color)
    {
        if (!$color) return '#000';
        $color = trim($color);

        if (str_starts_with($color, 'rgb')) {
            preg_match_all('/\d+/', $color, $rgb);
            [$r, $g, $b] = array_map('intval', $rgb[0]);
        } elseif (str_starts_with($color, '#')) {
            $hex = str_replace('#', '', $color);
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
        } else {
            $r = 68;
            $g = 68;
            $b = 68;
        }

        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
        return $yiq >= 128 ? '#000' : '#fff';
    }
}
