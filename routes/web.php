<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('facilities', function () {
    return Inertia::render('Facilities');
})->name('facilities');

Route::get('patients', function () {
    return Inertia::render('Patients');
})->name('patients');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
