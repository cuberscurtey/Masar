<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Users
            ['users.view', 'عرض المستخدمين', 'users'],
            ['users.create', 'إضافة مستخدم', 'users'],
            ['users.update', 'تعديل المستخدمين', 'users'],
            ['users.delete', 'حذف المستخدمين', 'users'],
            ['users.search', 'البحث عن المستخدمين', 'users'],

            // Roles and Permissions
            ['roles.view', 'عرض الأدوار', 'roles'],
            ['roles.create', 'إنشاء دور', 'roles'],
            ['roles.update', 'تعديل الأدوار', 'roles'],
            ['roles.delete', 'حذف الأدوار', 'roles'],
            ['roles.search', 'البحث عن الأدوار', 'roles'],
            ['permissions.view', 'عرض الصلاحيات', 'permissions'],
            ['permissions.assign', 'إسناد الصلاحيات للأدوار', 'permissions'],

            // Destinations
            ['destinations.view', 'عرض الوجهات', 'destinations'],
            ['destinations.create', 'إضافة وجهة', 'destinations'],
            ['destinations.update', 'تعديل الوجهات', 'destinations'],
            ['destinations.delete', 'حذف الوجهات', 'destinations'],
            ['destinations.search', 'البحث عن الوجهات', 'destinations'],

            // Buses
            ['buses.view', 'عرض الحافلات', 'buses'],
            ['buses.create', 'إضافة حافلة', 'buses'],
            ['buses.update', 'تعديل الحافلات', 'buses'],
            ['buses.delete', 'حذف الحافلات', 'buses'],
            ['buses.search', 'البحث عن الحافلات', 'buses'],

            // Bus Seats
            ['bus_seats.view', 'عرض مقاعد الحافلات', 'bus_seats'],
            ['bus_seats.manage', 'إدارة مقاعد الحافلات', 'bus_seats'],

            // Drivers
            ['drivers.view', 'عرض السائقين', 'drivers'],
            ['drivers.create', 'إضافة سائق', 'drivers'],
            ['drivers.update', 'تعديل السائقين', 'drivers'],
            ['drivers.delete', 'حذف السائقين', 'drivers'],
            ['drivers.search', 'البحث عن السائقين', 'drivers'],

            // Trips
            ['trips.view', 'عرض الرحلات', 'trips'],
            ['trips.create', 'إنشاء رحلة', 'trips'],
            ['trips.update', 'تعديل الرحلات', 'trips'],
            ['trips.delete', 'حذف الرحلات', 'trips'],
            ['trips.search', 'البحث عن الرحلات', 'trips'],
            ['trips.assign_driver', 'تعيين سائق للرحلة', 'trips'],
            ['trips.assign_bus', 'تعيين حافلة للرحلة', 'trips'],
            ['trips.update_status', 'تحديث حالة الرحلة', 'trips'],
            ['trips.check_in', 'تسجيل صعود الركاب', 'trips'],
            ['trips.manifest', 'عرض كشف ركاب الرحلة', 'trips'],

            // Trip Seats
            ['trip_seats.view', 'عرض مقاعد الرحلات', 'trip_seats'],
            ['trip_seats.manage', 'إدارة مقاعد الرحلات', 'trip_seats'],

            // Bookings
            ['bookings.view', 'عرض الحجوزات', 'bookings'],
            ['bookings.create', 'إنشاء حجز', 'bookings'],
            ['bookings.update', 'تعديل الحجوزات', 'bookings'],
            ['bookings.cancel', 'إلغاء الحجوزات', 'bookings'],
            ['bookings.search', 'البحث عن الحجوزات', 'bookings'],
            ['bookings.confirm', 'تأكيد الحجوزات', 'bookings'],

            // Booking Passengers
            ['booking_passengers.view', 'عرض بيانات الركاب', 'booking_passengers'],
            ['booking_passengers.manage', 'إدارة بيانات الركاب', 'booking_passengers'],

            // Payments
            ['payments.view', 'عرض المدفوعات', 'payments'],
            ['payments.create', 'تسجيل دفعة', 'payments'],
            ['payments.verify', 'مراجعة المدفوعات', 'payments'],
            ['payments.refund', 'معالجة طلبات الاسترداد', 'payments'],
            ['payments.search', 'البحث عن المدفوعات', 'payments'],

            // Tickets
            ['tickets.view', 'عرض التذاكر', 'tickets'],
            ['tickets.print', 'طباعة التذاكر', 'tickets'],
            ['tickets.verify', 'التحقق من التذاكر', 'tickets'],

            // Branches
            ['branches.view', 'عرض الفروع', 'branches'],
            ['branches.create', 'إضافة فرع', 'branches'],
            ['branches.update', 'تعديل الفروع', 'branches'],
            ['branches.delete', 'حذف الفروع', 'branches'],
            ['branches.search', 'البحث عن الفروع', 'branches'],

            // Reports
            ['reports.view', 'عرض التقارير', 'reports'],
            ['reports.export', 'تصدير التقارير', 'reports'],

            // Cashier
            ['cashier.view', 'عرض الورديات النقدية', 'cashier'],
            ['cashier.manage', 'إدارة الورديات النقدية', 'cashier'],

            // Discounts
            ['discounts.view', 'عرض رموز الخصم', 'discounts'],
            ['discounts.create', 'إنشاء رمز خصم', 'discounts'],
            ['discounts.update', 'تعديل رموز الخصم', 'discounts'],
            ['discounts.delete', 'حذف رموز الخصم', 'discounts'],

            // Waitlist
            ['waitlist.view', 'عرض قوائم الانتظار', 'waitlist'],
            ['waitlist.manage', 'إدارة قوائم الانتظار', 'waitlist'],

            // Complaints
            ['complaints.view', 'عرض الشكاوى', 'complaints'],
            ['complaints.create', 'إنشاء شكوى', 'complaints'],
            ['complaints.update', 'تحديث الشكاوى', 'complaints'],
            ['complaints.manage', 'إدارة الشكاوى', 'complaints'],
            ['complaints.search', 'البحث عن الشكاوى', 'complaints'],

            // Settings
            ['settings.view', 'عرض إعدادات النظام', 'settings'],
            ['settings.update', 'تعديل إعدادات النظام', 'settings'],

            // Audit Log
            ['audit_logs.view', 'عرض سجل العمليات', 'audit_logs'],
        ];

        DB::transaction(function () use ($permissions) {

            // إنشاء الصلاحيات أو تحديث بياناتها
            foreach ($permissions as [$name, $displayName, $group]) {
                Permission::updateOrCreate(
                    ['name' => $name],
                    [
                        'display_name' => $displayName,
                        'group' => $group,
                    ]
                );
            }

            // صلاحيات العميل
            $customerPermissions = [
                'destinations.view',
                'destinations.search',
                'buses.view',
                'bus_seats.view',
                'trips.view',
                'trips.search',
                'trip_seats.view',
                'bookings.view',
                'bookings.create',
                'bookings.update',
                'bookings.cancel',
                'bookings.search',
                'booking_passengers.view',
                'booking_passengers.manage',
                'payments.view',
                'payments.create',
                'payments.search',
                'tickets.view',
                'complaints.view',
                'complaints.create',
                'complaints.search',
            ];

            // صلاحيات السائق
            $driverPermissions = [
                'trips.view',
                'trips.update_status',
            ];

            // صلاحيات مشرف الرحلة
            $supervisorPermissions = [
                'destinations.view',
                'buses.view',
                'bus_seats.view',
                'drivers.view',
                'trips.view',
                'trips.create',
                'trips.update',
                'trips.search',
                'trips.assign_driver',
                'trips.assign_bus',
                'trips.update_status',
                'trips.check_in',
                'trips.manifest',
                'trip_seats.view',
                'trip_seats.manage',
                'bookings.view',
                'bookings.search',
                'booking_passengers.view',
                'tickets.view',
                'tickets.print',
                'tickets.verify',
            ];

            // صلاحيات المحاسب
            $accountantPermissions = [
                'bookings.view',
                'bookings.search',
                'payments.view',
                'payments.verify',
                'payments.refund',
                'payments.search',
                'reports.view',
                'reports.export',
                'cashier.view',
                'cashier.manage',
            ];

            $rolePermissions = [
                'customer' => $customerPermissions,
                'driver' => $driverPermissions,
                'trip_supervisor' => $supervisorPermissions,
                'accountant' => $accountantPermissions,
            ];

            foreach ($rolePermissions as $roleName => $permissionNames) {

                $role = Role::where('name', $roleName)->firstOrFail();

                $permissionIds = Permission::whereIn(
                    'name',
                    $permissionNames
                )->pluck('id');

                if ($permissionIds->count() !== count($permissionNames)) {
                    throw new \RuntimeException(
                        "Some permissions are missing for role: {$roleName}"
                    );
                }

                // إضافة الصلاحيات دون حذف الروابط الموجودة
                $role->permissions()->syncWithoutDetaching(
                    $permissionIds->all()
                );
            }
        });
    }
}
