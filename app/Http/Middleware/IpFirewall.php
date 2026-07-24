<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\FirewallIp;
use Illuminate\Support\Facades\Auth;

class IpFirewall
{
    public function handle(Request $request, Closure $next): Response
    {
        $clientIp = $request->ip();

        // PENGAMAN: Cek apakah tabel whitelist kosong melompong?
        $totalWhitelist = FirewallIp::count();
        if ($totalWhitelist === 0) {
            // Jika belum ada aturan sama sekali, loloskan dulu agar admin tidak terkunci
            return $next($request);
        }

        // 1. Cek apakah IP client terdaftar di whitelist database
        $isAllowed = FirewallIp::where('ip_address', $clientIp)->exists();

        // (Opsional) Selalu izinkan IP Localhost/127.0.0.1 untuk jaga-jaga saat testing
        if (!$isAllowed && in_array($clientIp, ['127.0.0.1', '::1'])) {
            $isAllowed = true;
        }

        if (!$isAllowed) {
            // Jika IP tidak diizinkan, tendang/logout paksa jika sedang login
            if (Auth::check()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
            }

            // Tampilkan halaman blokir
            abort(403, 'Akses Ditolak! IP Address Anda (' . $clientIp . ') tidak memiliki izin untuk mengakses Aplikasi Keuangan ini.');
        }

        return $next($request);
    }
}