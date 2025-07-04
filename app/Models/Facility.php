<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
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
}
