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
            'name'              => 'string|max:255|required',
            'date_of_birth'     => 'string|nullable',
            'room_number'       => 'string|max:50|nullable',
            'type_of_consent'   => 'string|max:255|nullable',
            'primary_insurance' => 'string|max:255|nullable',
            'date_last_seen'    => 'date|nullable',
            'status'            => 'string|max:50|nullable',
        ]);

        $patient = Patient::create($validated);

        return response()->json($patient, 201);
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return response()->json(['message' => 'Patient deleted'], 200);
    }
}
