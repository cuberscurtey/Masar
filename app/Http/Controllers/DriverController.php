<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    // عرض جميع السائقين
    public function index()
    {
        $drivers = Driver::with('user')->get();

        return response()->json([
            "message" => "Drivers retrieved successfully",
            "data" => $drivers
        ], 200);
    }

    // عرض سائق واحد
    public function show($id)
    {
        $driver = Driver::with('user')->find($id);

        if (!$driver) {
            return response()->json([
                "message" => "Driver not found"
            ], 404);
        }

        return response()->json([
            "message" => "Driver retrieved successfully",
            "data" => $driver
        ], 200);
    }

    // إنشاء سائق وحساب دخول تلقائياً
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|unique:drivers,phone',
            'email' => 'required|email|unique:drivers,email|unique:users,email',
            'account_password' => 'required|string|min:8',
            'license_number' => 'required|string|unique:drivers,license_number',
            'license_expiry' => 'required|date',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'status' => 'sometimes|in:available,on_trip,leave,suspended',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $driver = DB::transaction(function () use ($request) {

            // الحصول على دور السائق من جدول roles
            $driverRole = Role::where('name', 'driver')->firstOrFail();

            // إنشاء حساب المستخدم وربطه بدور السائق
            $user = User::create([
                'name' => $request->full_name,
                'email' => $request->email,
                'password' => Hash::make($request->account_password),
                'role_id' => $driverRole->id,
            ]);

            // إنشاء سجل السائق وربطه بالحساب
            return Driver::create([
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'license_number' => $request->license_number,
                'license_expiry' => $request->license_expiry,
                'address' => $request->address,
                'emergency_contact' => $request->emergency_contact,
                'status' => $request->status ?? 'available',
                'user_id' => $user->id
            ]);
        });

        return response()->json([
            "message" => "Driver and account created successfully",
            "data" => $driver->load('user')
        ], 201);
    }

    // تحديث بيانات السائق وحسابه
    public function update(Request $request, $id)
    {
        $driver = Driver::find($id);

        if (!$driver) {
            return response()->json([
                "message" => "Driver not found"
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|unique:drivers,phone,' . $id,
            'email' => [
                'required',
                'email',
                Rule::unique('drivers', 'email')->ignore($id),
                Rule::unique('users', 'email')->ignore($driver->user_id),
            ],
            'account_password' => 'sometimes|nullable|string|min:8',
            'license_number' => 'required|string|unique:drivers,license_number,' . $id,
            'license_expiry' => 'required|date',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string',
            'status' => 'required|in:available,on_trip,leave,suspended',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        DB::transaction(function () use ($request, $driver) {

            $driver->update([
                'full_name' => $request->full_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'license_number' => $request->license_number,
                'license_expiry' => $request->license_expiry,
                'address' => $request->address,
                'emergency_contact' => $request->emergency_contact,
                'status' => $request->status,
            ]);

            // تحديث الحساب المرتبط إن وجد
            if ($driver->user_id) {
                $user = User::find($driver->user_id);

                if ($user) {
                    $user->name = $request->full_name;
                    $user->email = $request->email;

                    if ($request->filled('account_password')) {
                        $user->password = Hash::make(
                            $request->account_password
                        );
                    }

                    $user->save();
                }
            }
        });

        return response()->json([
            "message" => "Driver updated successfully",
            "data" => $driver->fresh()->load('user')
        ], 200);
    }

    // حذف سجل السائق
    public function destroy($id)
    {
        $driver = Driver::find($id);

        if (!$driver) {
            return response()->json([
                "message" => "Driver not found"
            ], 404);
        }

        $driver->delete();

        return response()->json([
            "message" => "Driver deleted successfully"
        ], 200);
    }

    // البحث عن السائقين
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'search' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $search = $request->search;

        $drivers = Driver::with('user')
            ->where(function ($query) use ($search) {
                $query->where('full_name', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%")
                    ->orWhere('license_number', 'LIKE', "%{$search}%");
            })
            ->get();

        return response()->json([
            'message' => 'Drivers search completed successfully',
            'data' => $drivers
        ], 200);
    }
}
