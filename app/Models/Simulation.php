<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simulation extends Model
{
    use HasFactory;

    protected $fillable = [
    'simulation_type',
    'status',
    

    // MITM
    'intercepted_packets',
    'exposed_credentials',

    // DDoS
    'ddos_mode',
    'target',
    'request_rate',
    'duration',
    'total_requests',

    // Shared
    'risk_level',
    'ai_explanation',

    // 🔥 REQUIRED FOR PHISHING
    'emails_sent',
    'clicked_links',
    'entered_details',
    'user_id',
];


    protected $casts = [
        'metadata' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
