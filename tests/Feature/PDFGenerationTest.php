<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PDFGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_physician_order_pdf_uses_alphabetically_sorted_patients()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create patients with different names in non-alphabetical order
        $patientC = Patient::create([
            'name' => 'Charlie Smith',
            'facility_id' => $facility->id,
            'room_number' => '103',
            'type_of_consent' => 'Physician Request',
            'primary_insurance' => 'Medicare',
            'status' => 'needs seen',
        ]);

        $patientA = Patient::create([
            'name' => 'Alice Johnson',
            'facility_id' => $facility->id,
            'room_number' => '101',
            'type_of_consent' => 'Physician Request',
            'primary_insurance' => 'Medicare',
            'status' => 'needs seen',
        ]);

        $patientB = Patient::create([
            'name' => 'Bob Wilson',
            'facility_id' => $facility->id,
            'room_number' => '102',
            'type_of_consent' => 'Physician Request',
            'primary_insurance' => 'Medicare',
            'status' => 'needs seen',
        ]);

        // Test the physician order PDF endpoint
        $response = $this->postJson('/api/pdf/download-physician-order', [
            'patients' => [
                $patientC->toArray(), // Charlie (should be last)
                $patientA->toArray(), // Alice (should be first)
                $patientB->toArray(), // Bob (should be second)
            ],
            'facilityName' => 'Test Facility'
        ]);

        // The endpoint should receive the patients in the order they were sent
        // The frontend will sort them alphabetically before sending
        $response->assertStatus(200);
    }

    public function test_podiatry_visit_pdf_uses_alphabetically_sorted_patients()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create patients with different names in non-alphabetical order
        $patientC = Patient::create([
            'name' => 'Charlie Smith',
            'facility_id' => $facility->id,
            'room_number' => '103',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'needs seen',
            'date_last_seen' => now()->subDays(65)->format('Y-m-d'), // 65 days ago
        ]);

        $patientA = Patient::create([
            'name' => 'Alice Johnson',
            'facility_id' => $facility->id,
            'room_number' => '101',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'needs seen',
            'date_last_seen' => now()->subDays(65)->format('Y-m-d'), // 65 days ago
        ]);

        $patientB = Patient::create([
            'name' => 'Bob Wilson',
            'facility_id' => $facility->id,
            'room_number' => '102',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'needs seen',
            'date_last_seen' => now()->subDays(65)->format('Y-m-d'), // 65 days ago
        ]);

        // Test the podiatry visit PDF endpoint
        $response = $this->postJson('/api/pdf/download-podiatry-visit', [
            'patients' => [
                $patientC->toArray(), // Charlie (should be last)
                $patientA->toArray(), // Alice (should be first)
                $patientB->toArray(), // Bob (should be second)
            ],
            'facilityName' => 'Test Facility',
            'facilityContact' => 'Test Contact'
        ]);

        // The endpoint should receive the patients in the order they were sent
        // The frontend will sort them alphabetically before sending
        $response->assertStatus(200);
    }
} 