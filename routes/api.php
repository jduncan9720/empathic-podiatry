<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\FacilityController;
use App\Http\Controllers\PdfController;

Route::get('/patients', [PatientController::class, 'index']);
Route::post('/patients', [PatientController::class, 'store']);
Route::put('/patients/{id}', [PatientController::class, 'update']);
Route::delete('/patients/{id}', [PatientController::class, 'destroy']);
Route::patch('/patients/{id}/restore', [PatientController::class, 'restore']);
Route::delete('/patients/{id}/force', [PatientController::class, 'forceDelete']);
Route::get('/patients/trashed', [PatientController::class, 'trashed']);

Route::get('/facilities/{facility}/patients', [FacilityController::class, 'patients']);

Route::get('/facilities', [FacilityController::class, 'index']);
Route::post('/facilities', [FacilityController::class, 'store']);
Route::put('/facilities/{id}', [FacilityController::class, 'update']);
Route::delete('/facilities/{id}', [FacilityController::class, 'destroy']);
Route::patch('/facilities/{id}/restore', [FacilityController::class, 'restore']);
Route::delete('/facilities/{id}/force', [FacilityController::class, 'forceDelete']);
Route::get('/facilities/trashed', [FacilityController::class, 'trashed']);

// PDF Routes
Route::post('/pdf/physician-order', [PdfController::class, 'generatePhysicianOrder']);
Route::post('/pdf/download-physician-order', [PdfController::class, 'downloadPhysicianOrder']);
Route::post('/pdf/podiatry-visit', [PdfController::class, 'generatePodiatryVisit']);
Route::post('/pdf/download-podiatry-visit', [PdfController::class, 'downloadPodiatryVisit']);
