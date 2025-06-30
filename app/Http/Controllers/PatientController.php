<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // Return all patients as JSON
    public function index()
    {
        return response()->json(Patient::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'required|string',
            'room_number' => 'required|string|max:50',
            'type_of_consent' => 'required|string|max:255',
            'primary_insurance' => 'required|string|max:255',
            'date_last_seen' => 'required|date',
            'status' => 'required|string|max:50',
        ]);

        $patient = Patient::create($validated);

        return response()->json($patient, 201);
    }
}
