<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceSensor extends Model
{
    use HasFactory;

    protected $fillable = ['device_id', 'sensor_name', 'mqtt_key', 'sensor_label', 'unit'];

    /**
     * Relasi ke Device
     */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
