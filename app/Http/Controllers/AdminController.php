<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function store(Request $request)
    {
        // التحقق من عدم وجود مدير أعلى مسبقاً
        $adminExists = User::whereHas('roleRecord', function ($query) {
            $query->where('is_super_admin', true);
        })->exists();

        if ($adminExists) {
            return response()->json([
                'message' => 'Admin account already exists.'
            ], 403);
        }

        // التحقق من مفتاح الإعداد السري
        $secret = config('app.admin_setup_key');

        $providedKey = (string) $request->header('X-Setup-Key');

        if (
            empty($secret) ||
            !hash_equals((string) $secret, $providedKey)
        ) {
            return response()->json([
                'message' => 'Unauthorized.'
            ], 401);
        }

        // التحقق من بيانات المدير
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        // إنشاء المدير داخل معاملة قاعدة بيانات
        return DB::transaction(function () use ($request) {

            $adminRole = Role::where('name', 'admin')
                ->where('is_super_admin', true)
                ->firstOrFail();

            $admin = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'role_id' => $adminRole->id,
            ]);

            return response()->json([
                'message' => 'Admin account created successfully.',

                'admin' => $admin->only([
                    'id',
                    'name',
                    'email',
                    'role_id'
                ]),
            ], 201);
        });
    }
}
