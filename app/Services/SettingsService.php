<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    public static function getSettings(): Setting
    {
        return Cache::rememberForever('settings', function () {
            return Setting::query()->first();
        });
    }

    public static function getDateFormat(): string
    {
        return self::getSettings()->date_format;
    }

    public static function getTimeFormat(): string
    {
        return self::getSettings()->time_format;
    }

    public static function getCurrency(): string
    {
        return self::getSettings()->currency;
    }
}
