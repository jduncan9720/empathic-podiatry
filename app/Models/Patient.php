<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

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
}
