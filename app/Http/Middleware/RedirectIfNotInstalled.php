<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotInstalled
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Storage::disk('local')->exists('install.txt')) {
            return redirect()->route('install.welcome');
        }

        return $next($request);
    }
}
