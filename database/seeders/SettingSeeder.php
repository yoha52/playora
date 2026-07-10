<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::create([
            'date_format' => 'd-M-Y',
            'time_format' => 'h:i A',
            'currency' => 'USD',
        ]);
    }
}
