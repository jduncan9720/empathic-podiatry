<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

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
}
