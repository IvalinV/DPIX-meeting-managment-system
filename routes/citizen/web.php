<?php

use App\Http\Controllers\Auth\NewPasswordController as AuthNewPasswordController;
use App\Http\Controllers\Citizen\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Citizen\Auth\NewPasswordController;
use App\Http\Controllers\Citizen\Auth\PasswordResetLinkController;
use App\Http\Controllers\Citizen\Auth\RegisteredUserController;
use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Route;

Route::prefix('citizen')->middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('citizen.register');
    
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('citizen.login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('citizen.password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('citizen.password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('citizen.password.reset');

    Route::post('reset-password', [AuthNewPasswordController::class, 'store'])
        ->name('citizen.password.store');
});

Route::middleware('auth:citizen')->group(function () {
    Route::get('meeting/request', [MeetingController::class, 'create'])->name('meeting.create');
    Route::post('meeting/store', [MeetingController::class, 'store'])->name('meeting.store');
    Route::post('/citizen/logout', [AuthenticatedSessionController::class, 'destroy'])->name('citizen.logout');
});
