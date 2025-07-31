<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_patient_via_api()
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Create a facility
        $facility = Facility::create([
            'name' => 'Test Facility',
            'address_one' => '123 Test St',
            'city' => 'Test City',
            'state' => 'UT',
            'zip' => '12345',
        ]);

        // Test data for new patient
        $patientData = [
            'name' => 'John Doe',
            'facility_id' => $facility->id,
            'date_of_birth' => '1990-01-01',
            'room_number' => '101',
            'type_of_consent' => 'Standard',
            'primary_insurance' => 'Medicare',
            'status' => 'needs seen',
        ];

        $response = $this->postJson('/api/patients', $patientData);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'John Doe',
                'facility_id' => $facility->id,
                'date_of_birth' => '1990-01-01',
                'room_number' => '101',
                'type_of_consent' => 'Standard',
                'primary_insurance' => 'Medicare',
                'status' => 'needs seen',
            ]);

        // Verify patient was created in database
        $this->assertDatabaseHas('patients', [
            'name' => 'John Doe',
            'facility_id' => $facility->id,
        ]);
    }

    public function test_cannot_create_patient_without_required_fields()
    {
        $response = $this->postJson('/api/patients', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'facility_id']);
    }

    public function test_cannot_create_patient_with_invalid_facility()
    {
        $response = $this->postJson('/api/patients', [
            'name' => 'John Doe',
            'facility_id' => 999, // Non-existent facility
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['facility_id']);
    }
} 