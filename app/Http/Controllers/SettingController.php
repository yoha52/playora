<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\GeneralService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function edit(): View
    {
        $setting = Setting::query()->first();
        $currencies = GeneralService::getCurrenciesForDropdown();

        return view('settings.edit', compact('setting', 'currencies'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'date_format' => ['required', 'string', 'max:50'],
            'time_format' => ['required', 'string', 'max:50'],
            'currency' => ['required', 'string', 'max:10'],
        ]);

        $setting = Setting::query()->first();
        $setting->update($validated);

        Cache::forget('settings');

        return redirect()->route('settings.edit')->with('success', __('general.settings_updated'));
    }
}
