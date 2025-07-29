<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_patient_with_needs_seen_status()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        $patientData = [
            'name' => 'Test Patient',
            'facility_id' => $facility->id,
            'room_number' => '101',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'needs seen',
        ];

        $response = $this->postJson('/api/patients', $patientData);
        
        $response->assertStatus(201);
        $response->assertJson([
            'name' => 'Test Patient',
            'status' => 'needs seen',
        ]);

        $this->assertDatabaseHas('patients', [
            'name' => 'Test Patient',
            'status' => 'needs seen',
        ]);
    }

    public function test_can_update_patient_status_to_needs_seen()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create a patient with initial status
        $patient = Patient::create([
            'name' => 'Test Patient',
            'facility_id' => $facility->id,
            'room_number' => '101',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'visit complete',
        ]);

        // Update status to "needs seen"
        $response = $this->putJson("/api/patients/{$patient->id}", [
            'status' => 'needs seen',
        ]);
        
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'needs seen',
        ]);

        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'status' => 'needs seen',
        ]);
    }

    public function test_api_returns_patients_with_needs_seen_status()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create patients with different statuses
        Patient::create([
            'name' => 'Patient 1',
            'facility_id' => $facility->id,
            'room_number' => '101',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'visit complete',
        ]);

        Patient::create([
            'name' => 'Patient 2',
            'facility_id' => $facility->id,
            'room_number' => '102',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'needs seen',
        ]);

        $response = $this->getJson('/api/patients');
        
        $response->assertStatus(200);
        $response->assertJsonCount(2);
        
        $patients = $response->json();
        $statuses = collect($patients)->pluck('status')->toArray();
        
        $this->assertContains('visit complete', $statuses);
        $this->assertContains('needs seen', $statuses);
    }

    public function test_can_create_patient_with_all_available_statuses()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        $availableStatuses = ['needs seen', 'deceased', 'discharged', 'refused', 'visit complete'];

        foreach ($availableStatuses as $status) {
            $patientData = [
                'name' => "Test Patient - {$status}",
                'facility_id' => $facility->id,
                'room_number' => '101',
                'type_of_consent' => 'Resident Request',
                'primary_insurance' => 'Medicare',
                'status' => $status,
            ];

            $response = $this->postJson('/api/patients', $patientData);
            $response->assertStatus(201);
            $response->assertJson(['status' => $status]);

            $this->assertDatabaseHas('patients', [
                'name' => "Test Patient - {$status}",
                'status' => $status,
            ]);
        }
    }
} 