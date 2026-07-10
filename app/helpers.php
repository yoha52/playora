<?php

use App\Services\SettingsService;
use Carbon\Carbon;

if (!function_exists('formatDate')) {
    /**
     * Format date to software-specific format
     * @param $date
     * @return string
     */
    function formatDate($date): string
    {
        return Carbon::parse($date)->format(SettingsService::getDateFormat());
    }
}

if (!function_exists('formatTime')) {
    /**
     * Format time to software-specific format
     * @param $time
     * @return string
     */
    function formatTime($time): string
    {
        return Carbon::parse($time)->format(SettingsService::getTimeFormat());
    }
}

if (!function_exists('formatDateTime')) {
    /**
     * Format datetime to software-specific format
     * @param $date
     * @return string
     */
    function formatDateTime($date): string
    {
        return Carbon::parse($date)->format(SettingsService::getDateFormat().' '.SettingsService::getTimeFormat());
    }
}

if (! function_exists('formatNumber')) {
    /**
     * format number for a better view
     * @param $number
     * @param int $decimals
     * @return string|null
     */
    function formatNumber($number, int $decimals = 2): ?string
    {
        if (is_null($number)) {
            return '';
        }

        $showDecimal = false;
        $parts = explode('.', $number);
        if (count($parts) > 1 && $parts[1] > 0) {
            $showDecimal = true;
        }
        if ($number <= 0) {
            $showDecimal = true;
        }

        return number_format(floatval($number), ($showDecimal ? $decimals : 0), '.', ',');
    }
}
