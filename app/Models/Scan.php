<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'target',
        'scan_mode',
        'auto_detect',
        'features',
        'ports',          // store parsed table
        'raw_output',
    ];

    protected $casts = [
        'features' => 'array',
        'ports' => 'array',   // cast parsed results to array
        'auto_detect' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
