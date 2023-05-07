<?php

use App\Http\Controllers\Citizen\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ProfileController;
use App\Models\Lawyer;
use App\Models\Meeting;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
})->name('dashboard');

Route::get('test', function(Request $request){

});


Route::get('search', [CitizenController::class, 'search']);

Route::middleware('auth:citizen')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/citizen/web.php';
require __DIR__.'/lawyer/web.php';
