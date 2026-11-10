<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'driver_id',
        'origin_id',
        'destination_id',
        'departure_at',
        'arrival_at',
        'price',
        'status'
    ];

    // العلاقة: الرحلة ترتبط بحافلة واحدة
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    // العلاقة: الرحلة ترتبط بسائق واحد
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    // العلاقة: نقطة انطلاق الرحلة
    public function origin()
    {
        return $this->belongsTo(Destination::class, 'origin_id');
    }

    // العلاقة: وجهة الوصول للرحلة
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    // العلاقة: الرحلة تمتلك عدة مقاعد
    public function seats()
    {
        return $this->hasMany(TripSeat::class);
    }
}
