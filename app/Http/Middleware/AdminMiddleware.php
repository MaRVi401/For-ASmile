<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan apakah status 'is_admin' bernilai true (1)
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request);
        }

        // Jika bukan admin, batalkan akses dan arahkan ke halaman login admin dengan pesan error
        Auth::logout();
        return redirect()->route('admin.login')->with('error', 'Akses ditolak! Anda bukan Admin.');
    }
}