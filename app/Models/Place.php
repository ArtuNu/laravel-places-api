<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Place extends Model
{
    use HasFactory;

    // Fields allowed for mass assignment
    protected $fillable = [
        'name',
        'slug',
        'city',
        'state',
    ];
}