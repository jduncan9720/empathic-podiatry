<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    // Return all facilities as JSON
    public function index()
    {
        return response()->json(Facility::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'string|max:255|required',
            'address_one'   => 'string|max:255|nullable',
            'address_two'   => 'string|max:255|nullable',
            'city'          => 'string|max:100|nullable',
            'state'         => 'string|max:100|nullable',
            'zip'           => 'string|max:20|nullable',
            'phone_one'     => 'string|max:20|nullable',
            'phone_two'     => 'string|max:20|nullable',
            'email'         => 'email|max:255|nullable',
            'contact_name'  => 'string|max:255|nullable',
        ]);

        $facility = Facility::create($validated);

        return response()->json($facility, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'          => 'string|max:255|required',
            'address_one'   => 'string|max:255|nullable',
            'address_two'   => 'string|max:255|nullable',
            'city'          => 'string|max:100|nullable',
            'state'         => 'string|max:100|nullable',
            'zip'           => 'string|max:20|nullable',
            'phone_one'     => 'string|max:20|nullable',
            'phone_two'     => 'string|max:20|nullable',
            'email'         => 'email|max:255|nullable',
            'contact_name'  => 'string|max:255|nullable',
        ]);

        $facility = Facility::findOrFail($id);
        $facility->update($validated);

        return response()->json($facility, 200);
    }

    public function destroy($id)
    {
        $facility= Facility::findOrFail($id);
        $facility->delete();

        return response()->json(['message' => 'Facility deleted'], 200);
    }

    public function patients(Facility $facility)
    {
        return response()->json($facility->patients);
    }
}
