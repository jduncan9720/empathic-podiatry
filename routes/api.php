<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\PdfController;

Route::get('/patients', [PatientController::class, 'index']);
Route::post('/patients', [PatientController::class, 'store']);
Route::put('/patients/{id}', [PatientController::class, 'update']);
Route::delete('/patients/{id}', [PatientController::class, 'destroy']);

Route::get('/facilities/{facility}/patients', [FacilityController::class, 'patients']);

Route::get('/facilities', [FacilityController::class, 'index']);
Route::post('/facilities', [FacilityController::class, 'store']);
Route::put('/facilities/{id}', [FacilityController::class, 'update']);
Route::delete('/facilities/{id}', [FacilityController::class, 'destroy']);

// PDF Routes
Route::post('/pdf/physician-order', [PdfController::class, 'generatePhysicianOrder']);
Route::post('/pdf/download-physician-order', [PdfController::class, 'downloadPhysicianOrder']);
