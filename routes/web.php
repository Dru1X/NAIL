<?php

use App\Http\Controllers\CompetitionController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\MatchResultController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('people', PersonController::class);
    Route::resource('competitions', CompetitionController::class);
    Route::resource('competitions.entries', EntryController::class)->scoped();
    Route::resource('competitions.stages.matches', MatchResultController::class)->scoped();
});

require __DIR__.'/auth.php';
