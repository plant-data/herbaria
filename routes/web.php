<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\SpecimenController;
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});



Route::get('/specimens', [SpecimenController::class, 'index'])->name('specimens.index');
Route::get('/specimens/all', [SpecimenController::class, 'getAllSpecimens'])->name('specimens.all');
Route::get('/specimens/{specimen}', [SpecimenController::class, 'getSpecimenDetail'])->name('specimens.detail');
Route::get('/specimen/{specimen}', [SpecimenController::class, 'show'])->name('specimens.show');


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
});
