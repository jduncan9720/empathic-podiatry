<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function generatePhysicianOrder(Request $request)
    {
        $patients = $request->input('patients', []);
        $facilityName = $request->input('facilityName', 'Spring Creek');
        
        // Convert patients array to objects if they're arrays
        $patientObjects = collect($patients)->map(function ($patient) {
            return (object) $patient;
        })->toArray();
        
        $pdf = Pdf::loadView('pdf.physician-order', [
            'patients' => $patientObjects,
            'facilityName' => $facilityName
        ]);
        
        return $pdf->stream('physician-order.pdf');
    }
    
    public function downloadPhysicianOrder(Request $request)
    {
        $patients = $request->input('patients', []);
        $facilityName = $request->input('facilityName', 'Spring Creek');
        
        // Convert patients array to objects if they're arrays
        $patientObjects = collect($patients)->map(function ($patient) {
            return (object) $patient;
        })->toArray();
        
        $pdf = Pdf::loadView('pdf.physician-order', [
            'patients' => $patientObjects,
            'facilityName' => $facilityName
        ]);
        
        return $pdf->download('physician-order.pdf');
    }
} 