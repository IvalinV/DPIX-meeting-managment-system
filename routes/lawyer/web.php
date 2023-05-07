<?php

use App\Http\Controllers\Auth\NewPasswordController as AuthNewPasswordController;
use App\Http\Controllers\Lawyer\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Lawyer\Auth\NewPasswordController;
use App\Http\Controllers\Lawyer\Auth\PasswordResetLinkController;
use App\Http\Controllers\Lawyer\Auth\RegisteredUserController;
use App\Http\Controllers\LawyerController;
use Illuminate\Support\Facades\Route;

Route::prefix('lawyer')->middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('lawyer.register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('lawyer.login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('lawyer.password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('lawyer.password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('lawyer.password.reset');

    Route::post('reset-password', [AuthNewPasswordController::class, 'store'])
        ->name('lawyer.password.store');
});

Route::middleware('auth:lawyer')->group(function () {
    Route::get('/lawyer/{lawyer}/meetings', [LawyerController::class, 'show'])->name('lawyer.meetings');
    Route::post('/lawyer/logout', [AuthenticatedSessionController::class, 'destroy'])->name('lawyer.logout');
    Route::post('meeting/update', [MeetingController::class, 'update'])->name('meeting.update');
});
