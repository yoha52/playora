<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\GroundController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UploadMediaController;
use App\Http\Controllers\UserController;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;

Route::middleware(['installed'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    Route::middleware(['auth', 'verified'])->group(function () {
        Route::get('dashboard', Dashboard::class)->name('dashboard');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::resource('categories', CategoryController::class);
        Route::resource('grounds', GroundController::class);
        Route::resource('courts', CourtController::class);
        Route::resource('bookings', BookingController::class);
        Route::get('bookings-available-slots', [BookingController::class, 'getAvailableSlots'])->name('bookings.available-slots');
        Route::post('bookings/{booking}/receive-payment', [BookingController::class, 'receivePayment'])->name('bookings.receive-payment');
        Route::post('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

        Route::prefix('reports')->name('reports.')->group(function () {
            Route::get('due-bookings', [ReportController::class, 'dueBookings'])->name('due-bookings');
            Route::get('ground-stats', [ReportController::class, 'groundStats'])->name('ground-stats');
            Route::get('court-stats', [ReportController::class, 'courtStats'])->name('court-stats');
            Route::get('category-stats', [ReportController::class, 'categoryStats'])->name('category-stats');
        });

        Route::get('settings', [SettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

        Route::post('upload-media', UploadMediaController::class)->name('upload-media');
        Route::get('users/search', [UserController::class, 'search'])->name('users.search');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
    });

    Route::get('/js/lang.js', function () {
        $lang = app()->getLocale();

        $files = glob(base_path('lang/'.$lang.'/*.php'));
        $strings = [];

        foreach ($files as $file) {
            $name = basename($file, '.php');
            $strings[$name] = require $file;
        }

        header('Content-Type: text/javascript');
        echo 'window.i18n = '.json_encode($strings).';';
        exit();
    })->name('assets.lang');

    require __DIR__.'/auth.php';
});
