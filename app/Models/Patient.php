<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'facility_id',
        'date_of_birth',
        'room_number',
        'type_of_consent',
        'primary_insurance',
        'date_last_seen',
        'status',
    ];

    public function facility()
    {
        return $this->belongsTo(\App\Models\Facility::class);
    }

    /**
     * Scope to include only soft deleted patients
     */
    public function scopeOnlyTrashed($query)
    {
        return $query->onlyTrashed();
    }

    /**
     * Scope to include both active and soft deleted patients
     */
    public function scopeWithTrashed($query)
    {
        return $query->withTrashed();
    }
}
