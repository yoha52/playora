<?php

use App\Http\Controllers\Install\InstallController;
use Illuminate\Support\Facades\Route;

Route::middleware('install')->prefix('install')->name('install.')->group(function () {
    Route::get('/', [InstallController::class, 'welcome'])->name('welcome');
    Route::get('/requirements', [InstallController::class, 'requirements'])->name('requirements');
    Route::get('/permissions', [InstallController::class, 'permissions'])->name('permissions');

    Route::get('/site-name', [InstallController::class, 'siteName'])->name('site-name');
    Route::post('/site-name', [InstallController::class, 'storeSiteName'])->name('site-name.store');

    Route::get('/database', [InstallController::class, 'database'])->name('database');
    Route::post('/database', [InstallController::class, 'storeDatabase'])->name('database.store');

    Route::get('/license', [InstallController::class, 'license'])->name('license');
    Route::post('/license', [InstallController::class, 'storeLicense'])->name('license.store');

    Route::get('/smtp', [InstallController::class, 'smtp'])->name('smtp');
    Route::post('/smtp', [InstallController::class, 'storeSmtp'])->name('smtp.store');

    Route::get('/admin', [InstallController::class, 'admin'])->name('admin');
    Route::post('/admin', [InstallController::class, 'storeAdmin'])->name('admin.store');

    Route::get('/complete', [InstallController::class, 'complete'])->name('complete');
});
