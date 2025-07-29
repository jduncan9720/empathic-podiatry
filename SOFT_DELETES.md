# Soft Deletes Implementation

This application now supports soft deletes for both Patients and Facilities. This means that when you "delete" a record, it's not actually removed from the database but marked as deleted with a `deleted_at` timestamp.

## How Soft Deletes Work

### Models
Both `Patient` and `Facility` models now use the `SoftDeletes` trait, which provides the following functionality:

- `delete()` - Soft deletes the record (sets `deleted_at` timestamp)
- `restore()` - Restores a soft deleted record
- `forceDelete()` - Permanently deletes the record from the database
- `withTrashed()` - Includes soft deleted records in queries
- `onlyTrashed()` - Only includes soft deleted records in queries

### Database Changes
- Added `deleted_at` column to both `patients` and `facilities` tables
- Updated foreign key constraint for `patients.facility_id` to use `restrict` instead of `cascade` to prevent accidental deletion of patients when a facility is soft deleted

## API Endpoints

### Patients
- `GET /api/patients` - Get all active patients
- `POST /api/patients` - Create a new patient
- `PUT /api/patients/{id}` - Update a patient
- `DELETE /api/patients/{id}` - Soft delete a patient
- `PATCH /api/patients/{id}/restore` - Restore a soft deleted patient
- `DELETE /api/patients/{id}/force` - Permanently delete a patient
- `GET /api/patients/trashed` - Get only soft deleted patients

### Facilities
- `GET /api/facilities` - Get all active facilities
- `POST /api/facilities` - Create a new facility
- `PUT /api/facilities/{id}` - Update a facility
- `DELETE /api/facilities/{id}` - Soft delete a facility
- `PATCH /api/facilities/{id}/restore` - Restore a soft deleted facility
- `DELETE /api/facilities/{id}/force` - Permanently delete a facility
- `GET /api/facilities/trashed` - Get only soft deleted facilities

## Usage Examples

### In Controllers
```php
// Soft delete a patient
$patient = Patient::find($id);
$patient->delete();

// Restore a soft deleted patient
$patient = Patient::withTrashed()->find($id);
$patient->restore();

// Permanently delete a patient
$patient = Patient::withTrashed()->find($id);
$patient->forceDelete();

// Get only soft deleted patients
$trashedPatients = Patient::onlyTrashed()->get();

// Get all patients (including soft deleted)
$allPatients = Patient::withTrashed()->get();
```

### In Blade Templates
```php
// Only active patients
@foreach(Patient::all() as $patient)
    {{ $patient->name }}
@endforeach

// Include soft deleted patients
@foreach(Patient::withTrashed()->get() as $patient)
    {{ $patient->name }} @if($patient->trashed())(Deleted)@endif
@endforeach
```

## Benefits

1. **Data Recovery**: Accidentally deleted records can be restored
2. **Audit Trail**: You can see when records were deleted
3. **Referential Integrity**: Related records are preserved
4. **Performance**: No need to maintain separate archive tables

## Important Notes

- Soft deleted records are automatically excluded from normal queries
- Use `withTrashed()` to include soft deleted records in queries
- Use `onlyTrashed()` to query only soft deleted records
- The `trashed()` method can be used to check if a model is soft deleted
- Foreign key constraints prevent deletion of patients when their facility is soft deleted 