<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingPassenger extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'full_name',
        'date_of_birth',
        'gender',
        'nationality',
        'document_type',
        'document_number',
        'document_expiry_date',
        'phone',
        'passenger_type',
        'fare_amount',
        'ticket_number'
    ];

    protected $appends = ['age'];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'document_expiry_date' => 'date',
            'fare_amount' => 'decimal:2'
        ];
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth
            ? $this->date_of_birth->age
            : null;
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function tripSeat()
    {
        return $this->hasOne(TripSeat::class);
    }
    
}
