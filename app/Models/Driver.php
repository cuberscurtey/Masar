<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'phone',
        'email',
        'license_number',
        'license_expiry',
        'address',
        'emergency_contact',
        'status',
        'user_id'
    ];

    protected function casts(): array
    {
        return [
            'license_expiry' => 'date',
        ];
    }

    // العلاقة: السائق يرتبط بحساب مستخدم واحد
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // العلاقة: السائق يمكن أن تكون لديه عدة رحلات
    public function trips()
    {
        return $this->hasMany(Trip::class);
    }
}
