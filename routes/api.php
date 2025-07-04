<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\FacilityController;

Route::get('/patients', [PatientController::class, 'index']);
Route::post('/patients', [PatientController::class, 'store']);

Route::get('/facilities/{facility}/patients', [FacilityController::class, 'patients']);

Route::delete('/patients/{id}', [PatientController::class, 'destroy']);

Route::get('/facilities', [FacilityController::class, 'index']);
Route::post('/facilities', [FacilityController::class, 'store']);
Route::delete('/facilities/{id}', [FacilityController::class, 'destroy']);
