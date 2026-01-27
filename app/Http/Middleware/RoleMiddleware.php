<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
     public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // 🔥 SUPER ADMIN LEWAT SEMUA
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // cek role sesuai parameter
        if (!in_array($user->role, $roles)) {
            return response()->json([
                'message' => 'Akses ditolak'
            ], 403);
        }

        return $next($request);
    }
}
