<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkspaceDoneButtonTest extends TestCase
{
    use RefreshDatabase;

    public function test_done_button_updates_date_last_seen_and_status()
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
            'status' => 'needs seen',
            'date_last_seen' => null,
        ]);

        // Simulate clicking the Done button by updating the patient
        $today = now()->format('Y-m-d');
        $response = $this->putJson("/api/patients/{$patient->id}", [
            'date_last_seen' => $today,
            'status' => 'visit complete',
        ]);
        
        $response->assertStatus(200);
        $response->assertJson([
            'date_last_seen' => $today,
            'status' => 'visit complete',
        ]);

        // Verify the database has been updated
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'date_last_seen' => $today,
            'status' => 'visit complete',
        ]);
    }

    public function test_done_button_works_with_existing_date_last_seen()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create a patient with existing date_last_seen
        $patient = Patient::create([
            'name' => 'Test Patient',
            'facility_id' => $facility->id,
            'room_number' => '101',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'needs seen',
            'date_last_seen' => '2024-01-15',
        ]);

        // Simulate clicking the Done button
        $today = now()->format('Y-m-d');
        $response = $this->putJson("/api/patients/{$patient->id}", [
            'date_last_seen' => $today,
            'status' => 'visit complete',
        ]);
        
        $response->assertStatus(200);
        $response->assertJson([
            'date_last_seen' => $today,
            'status' => 'visit complete',
        ]);

        // Verify the database has been updated with new date
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'date_last_seen' => $today,
            'status' => 'visit complete',
        ]);
    }
} 