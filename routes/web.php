<?php

use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth:citizen', 'verified'])->name('dashboard');

Route::middleware('auth:citizen')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth:citizen')->group(function () {
    Route::get('meeting/request', [MeetingController::class, 'create'])->name('meeting.create');
    Route::post('meeting/store', [MeetingController::class, 'store'])->name('meeting.store');
});

Route::get('test', function(){
    $test = now();

    return $test;
});

require __DIR__.'/auth.php';
require __DIR__.'/citizen/web.php';
require __DIR__.'/lawyer/web.php';
