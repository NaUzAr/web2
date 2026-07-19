<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'device_id', 'custom_name', 'is_favorite', 'notes'];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Device
     */
    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
