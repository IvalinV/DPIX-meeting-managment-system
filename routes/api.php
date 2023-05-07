<?php

use App\Http\Controllers\MeetingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//TODO:Figureout away of using this
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//TODO: Should be changed
Route::get('/meetings', function(){
    return \App\Http\Resources\MeetingResource::collection(\App\Models\Meeting::with('citizen')->paginate(10));
});

Route::get('meeting/search', [MeetingController::class, 'search'])->name('meeting.search');
Route::post('meeting/sort', [MeetingController::class, 'sort'])->name('meeting.sort');
Route::post('meeting/filter', [MeetingController::class, 'filter'])->name('meeting.filter');

