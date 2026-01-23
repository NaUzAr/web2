<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceScheduleData extends Model
{
    protected $table = 'device_schedule_data';

    protected $fillable = [
        'device_id',
        'slot_key',
        'on_time',
        'duration',
        'sector',
        'name',
        'days',
        'is_active',
    ];

    protected $casts = [
        'days' => 'array',
        'is_active' => 'boolean',
        'duration' => 'integer',
        'sector' => 'integer',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Parse schedule string from device format
     * Format: "14:27-1-2-PUPUK  Min, Sen, Sel, Rab, Kam, Jum, Sab"
     * or empty: "_:_-0-0-_  -"
     */
    public static function parseFromDevice(string $raw): array
    {
        // Empty schedule check
        if (str_starts_with($raw, '_:_') || empty(trim($raw))) {
            return [
                'is_active' => false,
                'on_time' => null,
                'duration' => 0,
                'sector' => 0,
                'name' => null,
                'days' => [],
            ];
        }

        // Parse format: "HH:MM-DURATION-SECTOR-NAME  DAYS"
        // Example: "14:27-1-2-PUPUK  Min, Sen, Sel, Rab, Kam, Jum, Sab"

        $result = [
            'is_active' => true,
            'on_time' => null,
            'duration' => 0,
            'sector' => 0,
            'name' => null,
            'days' => [],
        ];

        // Split by first occurrence of double space to separate name from days
        $parts = preg_split('/\s{2,}/', $raw, 2);

        if (count($parts) >= 2) {
            // Parse days from second part
            $daysStr = trim($parts[1]);
            if (!empty($daysStr) && $daysStr !== '-') {
                $result['days'] = array_map('trim', explode(',', $daysStr));
            }
        }

        // Parse first part: "HH:MM-DURATION-SECTOR-NAME"
        $mainPart = $parts[0] ?? '';
        $segments = explode('-', $mainPart, 4);

        if (count($segments) >= 4) {
            // Time (HH:MM)
            if (preg_match('/^\d{1,2}:\d{2}$/', $segments[0])) {
                $result['on_time'] = $segments[0] . ':00'; // Add seconds for TIME format
            }

            // Duration
            $result['duration'] = (int) ($segments[1] ?? 0);

            // Sector
            $result['sector'] = (int) ($segments[2] ?? 0);

            // Name
            $result['name'] = trim($segments[3] ?? '');
        }

        return $result;
    }

    /**
     * Get display-friendly time
     */
    public function getDisplayTimeAttribute(): string
    {
        if (!$this->on_time) {
            return '-';
        }
        return substr($this->on_time, 0, 5); // HH:MM only
    }

    /**
     * Get display-friendly days
     */
    public function getDisplayDaysAttribute(): string
    {
        if (empty($this->days)) {
            return '-';
        }
        return implode(', ', $this->days);
    }
}
