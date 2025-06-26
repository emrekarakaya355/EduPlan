<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogIpAddress
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $route = $request->path();
        $method = $request->method();
        $adi = Auth()?->user()?->adi;
        $soyadi = Auth()?->user()?->soyadi;
        Log::info("Ziyaretçi IP: $ip | Route: $route | Method: $method | Adi: $adi | Soyadi: $soyadi");

        return $next($request);
    }
}
