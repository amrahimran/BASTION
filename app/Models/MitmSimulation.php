<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MitmSimulation extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'intercepted_packets',
        'exposed_credentials',
        'risk_level',
    ];
}
