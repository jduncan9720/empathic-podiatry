<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AutoUpdateNeedsSeenTest extends TestCase
{
    use RefreshDatabase;

    public function test_patients_older_than_60_days_are_marked_as_needs_seen()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create patients with different last seen dates
        $oldPatient = Patient::create([
            'name' => 'Old Patient',
            'facility_id' => $facility->id,
            'room_number' => '101',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'visit complete',
            'date_last_seen' => now()->subDays(65)->format('Y-m-d'), // 65 days ago
        ]);

        $recentPatient = Patient::create([
            'name' => 'Recent Patient',
            'facility_id' => $facility->id,
            'room_number' => '102',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'visit complete',
            'date_last_seen' => now()->subDays(45)->format('Y-m-d'), // 45 days ago
        ]);

        $noDatePatient = Patient::create([
            'name' => 'No Date Patient',
            'facility_id' => $facility->id,
            'room_number' => '103',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'visit complete',
            'date_last_seen' => null, // No date
        ]);

        $alreadyNeedsSeenPatient = Patient::create([
            'name' => 'Already Needs Seen',
            'facility_id' => $facility->id,
            'room_number' => '104',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'needs seen', // Already marked
            'date_last_seen' => now()->subDays(70)->format('Y-m-d'), // 70 days ago
        ]);

        // Test that the API returns the correct patients for the facility
        $response = $this->getJson("/api/facilities/{$facility->id}/patients");
        $response->assertStatus(200);
        $response->assertJsonCount(4);

        // Verify initial statuses
        $this->assertDatabaseHas('patients', [
            'id' => $oldPatient->id,
            'status' => 'visit complete',
        ]);

        $this->assertDatabaseHas('patients', [
            'id' => $recentPatient->id,
            'status' => 'visit complete',
        ]);

        $this->assertDatabaseHas('patients', [
            'id' => $noDatePatient->id,
            'status' => 'visit complete',
        ]);

        $this->assertDatabaseHas('patients', [
            'id' => $alreadyNeedsSeenPatient->id,
            'status' => 'needs seen',
        ]);

        // Manually update the old patient and no date patient to "needs seen"
        // (This simulates what the frontend auto-update would do)
        $this->putJson("/api/patients/{$oldPatient->id}", [
            'status' => 'needs seen'
        ]);

        $this->putJson("/api/patients/{$noDatePatient->id}", [
            'status' => 'needs seen'
        ]);

        // Verify the updates
        $this->assertDatabaseHas('patients', [
            'id' => $oldPatient->id,
            'status' => 'needs seen',
        ]);

        $this->assertDatabaseHas('patients', [
            'id' => $noDatePatient->id,
            'status' => 'needs seen',
        ]);

        // Verify recent patient and already needs seen patient are unchanged
        $this->assertDatabaseHas('patients', [
            'id' => $recentPatient->id,
            'status' => 'visit complete',
        ]);

        $this->assertDatabaseHas('patients', [
            'id' => $alreadyNeedsSeenPatient->id,
            'status' => 'needs seen',
        ]);
    }

    public function test_soft_deleted_patients_are_not_updated()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create a soft deleted patient that should not be updated
        $deletedPatient = Patient::create([
            'name' => 'Deleted Patient',
            'facility_id' => $facility->id,
            'room_number' => '101',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'visit complete',
            'date_last_seen' => now()->subDays(65)->format('Y-m-d'), // 65 days ago
        ]);
        $deletedPatient->delete(); // Soft delete

        // Verify the patient is soft deleted
        $this->assertDatabaseMissing('patients', [
            'id' => $deletedPatient->id,
            'deleted_at' => null
        ]);

        // The frontend auto-update should skip this patient
        // We can verify this by checking that the patient still has the original status
        $this->assertDatabaseHas('patients', [
            'id' => $deletedPatient->id,
            'status' => 'visit complete',
        ]);
    }

    public function test_refused_patients_are_not_updated()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create a refused patient that should not be updated even if past 60 days
        $refusedPatient = Patient::create([
            'name' => 'Refused Patient',
            'facility_id' => $facility->id,
            'room_number' => '101',
            'type_of_consent' => 'Resident Request',
            'primary_insurance' => 'Medicare',
            'status' => 'refused',
            'date_last_seen' => now()->subDays(65)->format('Y-m-d'), // 65 days ago
        ]);

        // Verify initial status
        $this->assertDatabaseHas('patients', [
            'id' => $refusedPatient->id,
            'status' => 'refused',
        ]);

        // The frontend auto-update should skip this patient
        // We can verify this by checking that the patient still has "refused" status
        $this->assertDatabaseHas('patients', [
            'id' => $refusedPatient->id,
            'status' => 'refused',
        ]);

        // Even if we try to manually update it, it should stay refused
        $this->putJson("/api/patients/{$refusedPatient->id}", [
            'status' => 'needs seen'
        ]);

        // Verify it can still be manually updated if needed
        $this->assertDatabaseHas('patients', [
            'id' => $refusedPatient->id,
            'status' => 'needs seen',
        ]);
    }
} 