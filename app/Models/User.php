<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // علاقة المستخدم بالحجوزات
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    // علاقة المدير بالمدفوعات التي راجعها
    public function verifiedPayments(): HasMany
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }

    // التحقق من صلاحية المدير
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // التحقق من صلاحية العميل
    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    // التحقق من صلاحية السائق
    public function isDriver(): bool
    {
        return $this->role === 'driver';
    }

    // التحقق من صلاحية مشرف الرحلات
    public function isTripSupervisor(): bool
    {
        return $this->role === 'trip_supervisor';
    }

    // التحقق من صلاحية المحاسب
    public function isAccountant(): bool
    {
        return $this->role === 'accountant';
    }
}
