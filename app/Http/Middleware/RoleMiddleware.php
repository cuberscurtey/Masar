<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // المدير الأعلى يتجاوز قيود الأدوار
        if ($user->isAdmin()) {
            return $next($request);
        }

        // قراءة الدور من العلاقة الجديدة
        $userRole = $user->roleRecord?->name;

        if (!in_array($userRole, $roles, true)) {
            return response()->json([
                'message' => 'Unauthorized. You do not have permission to access this resource.',
            ], 403);
        }

        return $next($request);
    }
}
