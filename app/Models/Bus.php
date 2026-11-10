<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bus extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_number',
        'plate_number',
        'manufacturer',
        'model',
        'status'
    ];

    public function seats()
    {
        return $this->hasMany(BusSeat::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
