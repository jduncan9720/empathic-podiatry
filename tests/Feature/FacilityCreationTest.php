<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FacilityCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_facility_via_api()
    {
        // Create a user for authentication
        $user = User::factory()->create();

        // Test data for new facility
        $facilityData = [
            'name' => 'Test Healthcare Facility',
            'address_one' => '123 Medical Center Dr',
            'address_two' => 'Suite 100',
            'city' => 'Salt Lake City',
            'state' => 'UT',
            'zip' => '84101',
            'phone_one' => '801-555-0123',
            'phone_two' => '801-555-0124',
            'email' => 'contact@testfacility.com',
            'contact_name' => 'Dr. Jane Smith',
        ];

        $response = $this->postJson('/api/facilities', $facilityData);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'Test Healthcare Facility',
                'address_one' => '123 Medical Center Dr',
                'address_two' => 'Suite 100',
                'city' => 'Salt Lake City',
                'state' => 'UT',
                'zip' => '84101',
                'phone_one' => '801-555-0123',
                'phone_two' => '801-555-0124',
                'email' => 'contact@testfacility.com',
                'contact_name' => 'Dr. Jane Smith',
            ]);

        // Verify facility was created in database
        $this->assertDatabaseHas('facilities', [
            'name' => 'Test Healthcare Facility',
            'contact_name' => 'Dr. Jane Smith',
        ]);
    }

    public function test_cannot_create_facility_without_required_fields()
    {
        $response = $this->postJson('/api/facilities', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    public function test_can_create_facility_with_minimal_data()
    {
        $facilityData = [
            'name' => 'Minimal Facility',
        ];

        $response = $this->postJson('/api/facilities', $facilityData);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'Minimal Facility',
            ]);

        // Verify facility was created in database
        $this->assertDatabaseHas('facilities', [
            'name' => 'Minimal Facility',
        ]);
    }
} 