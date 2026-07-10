<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\GroundController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    // Auth routes
    Route::post('sign-up', [AuthController::class, 'signUp']);
    Route::post('verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('resend-otp', [AuthController::class, 'resendOtp']);
    Route::post('sign-in', [AuthController::class, 'signIn']);
    Route::post('update-password', [AuthController::class, 'updatePassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('categories', [CategoryController::class, 'index']);

        Route::get('grounds/popular', [GroundController::class, 'popular']);
        Route::get('grounds/nearby', [GroundController::class, 'nearby']);
        Route::get('grounds/category', [GroundController::class, 'byCategory']);
        Route::get('ground-details', [GroundController::class, 'show'])->name('api.grounds.show');

        Route::get('booking/available-slots', [BookingController::class, 'availableSlots']);
        Route::get('bookings/upcoming', [BookingController::class, 'upcoming']);
        Route::get('bookings/completed', [BookingController::class, 'completed']);
        Route::get('booking-details', [BookingController::class, 'show']);
        Route::post('new-booking', [BookingController::class, 'store']);

        Route::get('get-profile', [AuthController::class, 'getProfile']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
    });
});
