<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'trip_id',
        'total_amount',
        'currency',
        'status',
        'expires_at'
    ];

    protected function casts(): array
    {
        return [
            'total_amount' => 'decimal:2',
            'expires_at' => 'datetime'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function passengers()
    {
        return $this->hasMany(BookingPassenger::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
