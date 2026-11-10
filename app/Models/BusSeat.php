<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusSeat extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'seat_number',
        'row_number',
        'column_number',
        'seat_type',
        'is_active'
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean'
        ];
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
    public function tripSeats()
{
    return $this->hasMany(TripSeat::class);
}
}
