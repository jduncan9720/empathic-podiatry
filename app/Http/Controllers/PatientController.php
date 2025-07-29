<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    // Return all patients as JSON
    public function index(Request $request)
    {
        $includeTrashed = $request->boolean('include_trashed');
        
        if ($includeTrashed) {
            return response()->json(Patient::withTrashed()->get());
        }
        
        return response()->json(Patient::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => 'string|max:255|required',
            'facility_id'       => 'required|exists:facilities,id',
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

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        
        // Define validation rules for each field that might be updated
        $validationRules = [];
        
        if ($request->has('name')) {
            $validationRules['name'] = 'string|max:255';
        }
        if ($request->has('facility_id')) {
            $validationRules['facility_id'] = 'exists:facilities,id';
        }
        if ($request->has('date_of_birth')) {
            $validationRules['date_of_birth'] = 'string|nullable';
        }
        if ($request->has('room_number')) {
            $validationRules['room_number'] = 'string|max:50|nullable';
        }
        if ($request->has('type_of_consent')) {
            $validationRules['type_of_consent'] = 'string|max:255|nullable';
        }
        if ($request->has('primary_insurance')) {
            $validationRules['primary_insurance'] = 'string|max:255|nullable';
        }
        if ($request->has('date_last_seen')) {
            $validationRules['date_last_seen'] = 'date|nullable';
        }
        if ($request->has('status')) {
            $validationRules['status'] = 'string|max:50|nullable';
        }
        
        // Only validate if there are fields to validate
        if (!empty($validationRules)) {
            $validated = $request->validate($validationRules);
            $patient->update($validated);
        }

        return response()->json($patient, 200);
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return response()->json(['message' => 'Patient deleted'], 200);
    }

    /**
     * Restore a soft deleted patient
     */
    public function restore($id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);
        $patient->restore();

        return response()->json(['message' => 'Patient restored', 'patient' => $patient], 200);
    }

    /**
     * Permanently delete a patient
     */
    public function forceDelete($id)
    {
        $patient = Patient::withTrashed()->findOrFail($id);
        $patient->forceDelete();

        return response()->json(['message' => 'Patient permanently deleted'], 200);
    }

    /**
     * Get only soft deleted patients
     */
    public function trashed()
    {
        return response()->json(Patient::onlyTrashed()->get());
    }
}
