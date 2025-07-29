<?php

namespace Tests\Feature;

use App\Models\Patient;
use App\Models\Facility;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SoftDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_patient_soft_delete()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create a patient
        $patient = Patient::create([
            'name' => 'John Doe',
            'facility_id' => $facility->id,
            'room_number' => '101',
        ]);

        // Verify patient exists
        $this->assertDatabaseHas('patients', ['id' => $patient->id]);

        // Soft delete the patient
        $patient->delete();

        // Verify patient is soft deleted (not in normal queries)
        $this->assertDatabaseMissing('patients', [
            'id' => $patient->id,
            'deleted_at' => null
        ]);

        // Verify patient still exists in database with deleted_at timestamp
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
        ]);
        $this->assertNotNull($patient->fresh()->deleted_at);

        // Verify patient is not returned in normal queries
        $this->assertNull(Patient::find($patient->id));

        // Verify patient is returned with withTrashed()
        $this->assertNotNull(Patient::withTrashed()->find($patient->id));

        // Restore the patient
        $patient->restore();

        // Verify patient is restored
        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'deleted_at' => null
        ]);
        $this->assertNotNull(Patient::find($patient->id));
    }

    public function test_facility_soft_delete()
    {
        // Create a facility
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Verify facility exists
        $this->assertDatabaseHas('facilities', ['id' => $facility->id]);

        // Soft delete the facility
        $facility->delete();

        // Verify facility is soft deleted (not in normal queries)
        $this->assertDatabaseMissing('facilities', [
            'id' => $facility->id,
            'deleted_at' => null
        ]);

        // Verify facility still exists in database with deleted_at timestamp
        $this->assertDatabaseHas('facilities', [
            'id' => $facility->id,
        ]);
        $this->assertNotNull($facility->fresh()->deleted_at);

        // Verify facility is not returned in normal queries
        $this->assertNull(Facility::find($facility->id));

        // Verify facility is returned with withTrashed()
        $this->assertNotNull(Facility::withTrashed()->find($facility->id));

        // Restore the facility
        $facility->restore();

        // Verify facility is restored
        $this->assertDatabaseHas('facilities', [
            'id' => $facility->id,
            'deleted_at' => null
        ]);
        $this->assertNotNull(Facility::find($facility->id));
    }

    public function test_force_delete()
    {
        // Create a facility first
        $facility = Facility::create([
            'name' => 'Test Facility',
            'city' => 'Test City',
        ]);

        // Create a patient
        $patient = Patient::create([
            'name' => 'John Doe',
            'facility_id' => $facility->id,
            'room_number' => '101',
        ]);

        // Soft delete the patient
        $patient->delete();

        // Verify patient is soft deleted
        $this->assertDatabaseMissing('patients', [
            'id' => $patient->id,
            'deleted_at' => null
        ]);

        // Force delete the patient
        $patient->forceDelete();

        // Verify patient is permanently deleted
        $this->assertDatabaseMissing('patients', ['id' => $patient->id]);
        $this->assertNull(Patient::withTrashed()->find($patient->id));
    }
}
