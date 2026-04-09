<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalSensorRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'sensor_key',
        'min_value',
        'max_value',
        'is_active',
    ];

    protected $casts = [
        'min_value' => 'float',
        'max_value' => 'float',
        'is_active' => 'boolean',
    ];
}
