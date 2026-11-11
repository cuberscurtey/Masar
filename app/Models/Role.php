<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_super_admin',
    ];

    protected function casts(): array
    {
        return [
            'is_super_admin' => 'boolean',
        ];
    }

    // الدور الواحد يمكن أن يمتلك عدة مستخدمين
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // الدور الواحد يمتلك عدة صلاحيات
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_has_permissions'
        )->withTimestamps();
    }
}
