<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'schedule_name',
        'schedule_label',
        'output_key',
        'schedule_mode',
        'max_slots',
        'max_sectors',
    ];

    /**
     * Relasi ke Device
     */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Get schedule mode label
     */
    public function getModeLabel(): string
    {
        return match ($this->schedule_mode) {
            'time' => 'Waktu Saja',
            'time_days' => 'Waktu + Hari',
            'time_days_sector' => 'Waktu + Hari + Sektor',
            default => $this->schedule_mode,
        };
    }
}
