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
    $test = Meeting::search($request->search)->query(function ($query) {
        $query
            ->join('citizens', 'meetings.citizen_id', 'citizens.id')
            ->join('lawyers', 'meetings.lawyer_id', 'lawyers.id')
            ->select(['meetings.id', 'meetings.date', 'lawyers.first_name as lawyer', 'citizens.first_name as citizen'])
            ->orderBy('meetings.date', 'DESC');
    })
    ->get();
    return response()->json($test);
});

Route::get('search', [CitizenController::class, 'search']);

Route::middleware('auth:citizen')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth:citizen')->group(function () {
    Route::get('meeting/request', [MeetingController::class, 'create'])->name('meeting.create');
    Route::post('meeting/store', [MeetingController::class, 'store'])->name('meeting.store');
    Route::post('/citizen/logout', [AuthenticatedSessionController::class, 'destroy'])->name('citizen.logout');
});
Route::post('meeting/update', [MeetingController::class, 'update'])->name('meeting.update')->middleware('auth:lawyer');


require __DIR__.'/auth.php';
require __DIR__.'/citizen/web.php';
require __DIR__.'/lawyer/web.php';
