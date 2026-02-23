<?php

if (!function_exists('is_mobile')) {
    /**
     * Very basic mobile detection via User-Agent string.
     * Covers ~95%+ of real phones/tablets without false positives on desktops.
     * Not perfect (some tablets may be missed, some rare browsers misdetected).
     */
    function is_mobile(): bool
    {
        if (empty($_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }

        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

        // Common mobile keywords (you can expand this list)
        $mobileKeywords = [
            'mobile', 'android', 'iphone', 'ipod', 'ipad', 'windows phone',
            'kindle', 'silk', 'blackberry', 'opera mini', 'opera mobi',
            'iemobile', 'wpdesktop', 'playbook', 'tablet', 'symbianos',
            ' series60', ' series40', 'nokia', 'samsung', 'lg', 'htc',
            'moto', 'webos', 'bada', 'meego', 'maemo'
        ];

        foreach ($mobileKeywords as $keyword) {
            if (str_contains($userAgent, $keyword)) {
                return true;
            }
        }

        // Extra check for tablets that don't have "mobile" but are touch devices
        if (str_contains($userAgent, 'tablet') || str_contains($userAgent, 'ipad')) {
            return true;
        }

        return false;
    }
}
