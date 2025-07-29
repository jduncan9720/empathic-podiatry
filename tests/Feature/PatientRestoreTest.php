<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientRestoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_restored_patient_gets_needs_seen_status()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create a patient with a different status
        $patient = Patient::create([
            'name' => 'Test Patient',
            'facility_id' => $facility->id,
            'room_number' => '101',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'visit complete',
        ]);

        // Soft delete the patient
        $patient->delete();

        // Verify patient is soft deleted
        $this->assertDatabaseMissing('patients', [
            'id' => $patient->id,
            'deleted_at' => null
        ]);

        // Restore the patient
        $response = $this->patchJson("/api/patients/{$patient->id}/restore");
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Patient restored']);

        // Verify patient is restored and status is set to "needs seen"
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'deleted_at' => null,
            'status' => 'needs seen'
        ]);

        // Verify the response includes the updated patient data
        $response->assertJson([
            'patient' => [
                'id' => $patient->id,
                'status' => 'needs seen'
            ]
        ]);
    }

    public function test_restored_patient_with_deceased_status_gets_needs_seen_status()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create a patient with "deceased" status
        $patient = Patient::create([
            'name' => 'Test Patient',
            'facility_id' => $facility->id,
            'room_number' => '101',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'deceased',
        ]);

        // Soft delete the patient
        $patient->delete();

        // Restore the patient
        $response = $this->patchJson("/api/patients/{$patient->id}/restore");
        $response->assertStatus(200);

        // Verify patient is restored and status is changed to "needs seen"
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'deleted_at' => null,
            'status' => 'needs seen'
        ]);
    }

    public function test_restored_patient_with_discharged_status_gets_needs_seen_status()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create a patient with "discharged" status
        $patient = Patient::create([
            'name' => 'Test Patient',
            'facility_id' => $facility->id,
            'room_number' => '101',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'discharged',
        ]);

        // Soft delete the patient
        $patient->delete();

        // Restore the patient
        $response = $this->patchJson("/api/patients/{$patient->id}/restore");
        $response->assertStatus(200);

        // Verify patient is restored and status is changed to "needs seen"
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'deleted_at' => null,
            'status' => 'needs seen'
        ]);
    }
} 