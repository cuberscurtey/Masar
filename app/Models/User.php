<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'role_id'])]
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

    // علاقة المستخدم بدوره
    public function roleRecord(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
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

    // التحقق من المدير الأعلى
    public function isAdmin(): bool
    {
        return (bool) $this->roleRecord?->is_super_admin;
    }

    // التحقق من العميل
    public function isCustomer(): bool
    {
        return $this->roleRecord?->name === 'customer';
    }

    // التحقق من السائق
    public function isDriver(): bool
    {
        return $this->roleRecord?->name === 'driver';
    }

    // التحقق من مشرف الرحلات
    public function isTripSupervisor(): bool
    {
        return $this->roleRecord?->name === 'trip_supervisor';
    }

    // التحقق من المحاسب
    public function isAccountant(): bool
    {
        return $this->roleRecord?->name === 'accountant';
    }

    // التحقق من امتلاك صلاحية معينة
    public function hasPermission(string $permission): bool
    {
        if ($this->roleRecord?->is_super_admin) {
            return true;
        }

        return $this->roleRecord()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('permissions.name', $permission);
            })
            ->exists();
    }
}
