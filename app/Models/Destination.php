<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'governorate',
        'description',
        'status'
    ];

    public function originTrips()
    {
        return $this->hasMany(Trip::class, 'origin_id');
    }

    public function destinationTrips()
    {
        return $this->hasMany(Trip::class, 'destination_id');
    }
}
