<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'address_one',
        'address_two',
        'city',
        'state',
        'zip',
        'phone_one',
        'phone_two',
        'email',
        'contact_name',
    ];

    public function patients()
    {
        return $this->hasMany(\App\Models\Patient::class);
    }

    /**
     * Scope to include only soft deleted facilities
     */
    public function scopeOnlyTrashed($query)
    {
        return $query->onlyTrashed();
    }

    /**
     * Scope to include both active and soft deleted facilities
     */
    public function scopeWithTrashed($query)
    {
        return $query->withTrashed();
    }
}
