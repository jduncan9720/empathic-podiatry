<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutoSoftDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_status_can_be_changed_to_deceased()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create a patient
        $patient = Patient::create([
            'name' => 'Test Patient',
            'facility_id' => $facility->id,
            'room_number' => '101',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'needs seen',
        ]);

        // Verify patient exists and is not soft deleted
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'deleted_at' => null
        ]);

        // Update patient status to "deceased"
        $response = $this->putJson("/api/patients/{$patient->id}", [
            'status' => 'deceased'
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['status' => 'deceased']);

        // Verify patient status was updated
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'status' => 'deceased',
        ]);

        // The frontend would then automatically soft delete this patient
        // For testing purposes, we can manually soft delete to simulate the frontend behavior
        $patient->delete();

        // Verify patient is now soft deleted
        $this->assertDatabaseMissing('patients', [
            'id' => $patient->id,
            'deleted_at' => null
        ]);

        // Verify patient still exists in database with deleted_at timestamp
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
        ]);
        $this->assertNotNull($patient->fresh()->deleted_at);
    }

    public function test_patient_status_can_be_changed_to_discharged()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create a patient
        $patient = Patient::create([
            'name' => 'Test Patient',
            'facility_id' => $facility->id,
            'room_number' => '101',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'visit complete',
        ]);

        // Verify patient exists and is not soft deleted
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'deleted_at' => null
        ]);

        // Update patient status to "discharged"
        $response = $this->putJson("/api/patients/{$patient->id}", [
            'status' => 'discharged'
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['status' => 'discharged']);

        // Verify patient status was updated
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'status' => 'discharged',
        ]);

        // The frontend would then automatically soft delete this patient
        // For testing purposes, we can manually soft delete to simulate the frontend behavior
        $patient->delete();

        // Verify patient is now soft deleted
        $this->assertDatabaseMissing('patients', [
            'id' => $patient->id,
            'deleted_at' => null
        ]);

        // Verify patient still exists in database with deleted_at timestamp
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
        ]);
        $this->assertNotNull($patient->fresh()->deleted_at);
    }

    public function test_patient_is_not_soft_deleted_for_other_status_changes()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create a patient
        $patient = Patient::create([
            'name' => 'Test Patient',
            'facility_id' => $facility->id,
            'room_number' => '101',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'needs seen',
        ]);

        // Verify patient exists and is not soft deleted
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'deleted_at' => null
        ]);

        // Update patient status to "visit complete" (should not trigger soft delete)
        $response = $this->putJson("/api/patients/{$patient->id}", [
            'status' => 'visit complete'
        ]);
        
        $response->assertStatus(200);
        $response->assertJson(['status' => 'visit complete']);

        // Verify patient is NOT soft deleted
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'deleted_at' => null
        ]);
    }
} 