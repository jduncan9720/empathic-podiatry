<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientSoftDeleteUITest extends TestCase
{
    use RefreshDatabase;

    public function test_api_returns_all_patients_including_trashed()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create active patients
        $activePatient = Patient::create([
            'name' => 'Active Patient',
            'facility_id' => $facility->id,
            'room_number' => '101',
        ]);

        // Create and soft delete a patient
        $deletedPatient = Patient::create([
            'name' => 'Deleted Patient',
            'facility_id' => $facility->id,
            'room_number' => '102',
        ]);
        $deletedPatient->delete();

        // Test default endpoint (should not include trashed)
        $response = $this->getJson('/api/patients');
        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonPath('0.name', 'Active Patient');

        // Test with include_trashed parameter
        $response = $this->getJson('/api/patients?include_trashed=true');
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        
        // Check that both patients are returned
        $patients = $response->json();
        $patientNames = collect($patients)->pluck('name')->toArray();
        $this->assertContains('Active Patient', $patientNames);
        $this->assertContains('Deleted Patient', $patientNames);
        
        // Check that deleted patient has deleted_at field
        $deletedPatientData = collect($patients)->firstWhere('name', 'Deleted Patient');
        $this->assertNotNull($deletedPatientData['deleted_at']);
    }

    public function test_restore_patient_endpoint()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create and soft delete a patient
        $patient = Patient::create([
            'name' => 'Test Patient',
            'facility_id' => $facility->id,
            'room_number' => '101',
        ]);
        $patient->delete();

        // Verify patient is soft deleted
        $this->assertDatabaseMissing('patients', [
            'id' => $patient->id,
            'deleted_at' => null
        ]);

        // Test restore endpoint
        $response = $this->patchJson("/api/patients/{$patient->id}/restore");
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Patient restored']);

        // Verify patient is restored
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'deleted_at' => null
        ]);
    }

    public function test_force_delete_patient_endpoint()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create and soft delete a patient
        $patient = Patient::create([
            'name' => 'Test Patient',
            'facility_id' => $facility->id,
            'room_number' => '101',
        ]);
        $patient->delete();

        // Test force delete endpoint
        $response = $this->deleteJson("/api/patients/{$patient->id}/force");
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Patient permanently deleted']);

        // Verify patient is permanently deleted
        $this->assertDatabaseMissing('patients', ['id' => $patient->id]);
    }
} 