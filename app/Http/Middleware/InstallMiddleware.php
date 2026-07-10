<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class InstallMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Storage::disk('local')->exists('install.txt')) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}
