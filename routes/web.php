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

    // Temporary protected debug endpoint — set DEBUG_TOKEN in Render and call /_debug?token=YOUR_TOKEN
    Route::get('/_debug', function () {
        try {
            $token = env('DEBUG_TOKEN');
            if (empty($token) || request()->query('token') !== $token) {
                abort(404);
            }

            $result = [
                'app' => 'running',
                'env' => env('APP_ENV'),
                'app_debug' => env('APP_DEBUG'),
            ];

            $result['env_file'] = file_exists(base_path('.env')) ? 'present' : 'missing';
            $result['app_key_env'] = env('APP_KEY') ? 'set' : 'missing';
            $result['app_key_config'] = config('app.key') ?: null;

            // storage writable test
            try {
                $testPath = storage_path('debug_write_test.txt');
                file_put_contents($testPath, date('c'));
                if (file_exists($testPath)) {
                    unlink($testPath);
                    $result['storage_writable'] = true;
                } else {
                    $result['storage_writable'] = false;
                }
            } catch (\Throwable $e) {
                $result['storage_writable'] = false;
                $result['storage_write_error'] = $e->getMessage();
            }

            $result['db_connection_env'] = env('DB_CONNECTION');

            try {
                \Illuminate\Support\Facades\DB::connection()->getPdo();
                $result['database'] = 'ok';
            } catch (\Throwable $e) {
                $result['database'] = 'error';
                $result['db_message'] = $e->getMessage();
            }

            return response()->json($result);
        } catch (\Throwable $e) {
            return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
        }
    });
