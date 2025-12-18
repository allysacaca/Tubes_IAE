<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Cek jika user terautentikasi dan adalah admin
        // Anda perlu menyesuaikan dengan logika admin Anda
        if (!Auth::check() || !$request->user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        return $next($request);
    }
}
