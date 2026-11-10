<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id',
        'bus_seat_id',
        'booking_passenger_id',
        'status',
        'hold_expires_at'
    ];

    protected function casts(): array
    {
        return [
            'hold_expires_at' => 'datetime'
        ];
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function busSeat()
    {
        return $this->belongsTo(BusSeat::class);
    }

    public function bookingPassenger()
    {
        return $this->belongsTo(BookingPassenger::class);
    }
}
