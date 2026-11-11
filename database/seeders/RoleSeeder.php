<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'مدير النظام',
                'description' => 'مدير النظام الأعلى ويمتلك جميع الصلاحيات.',
                'is_super_admin' => true,
            ],
            [
                'name' => 'customer',
                'display_name' => 'العميل',
                'description' => 'مستخدم خدمات الحجز والسفر.',
                'is_super_admin' => false,
            ],
            [
                'name' => 'driver',
                'display_name' => 'السائق',
                'description' => 'المسؤول عن قيادة الحافلة وتنفيذ الرحلة.',
                'is_super_admin' => false,
            ],
            [
                'name' => 'trip_supervisor',
                'display_name' => 'مشرف الرحلة',
                'description' => 'المسؤول عن متابعة الرحلات والركاب.',
                'is_super_admin' => false,
            ],
            [
                'name' => 'accountant',
                'display_name' => 'المحاسب',
                'description' => 'المسؤول عن المدفوعات والمراجعة المالية.',
                'is_super_admin' => false,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                [
                    'display_name' => $role['display_name'],
                    'description' => $role['description'],
                    'is_super_admin' => $role['is_super_admin'],
                ]
            );
        }
    }
}
