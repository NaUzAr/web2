<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceOutput extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'output_name',
        'output_label',
        'output_type',
        'unit',
        'default_value',
        'current_value'
    ];

    /**
     * Cast attributes to proper types
     */
    protected $casts = [
        'default_value' => 'float',
        'current_value' => 'float',
    ];

    /**
     * Relasi ke Device
     */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
