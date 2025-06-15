<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsCompany
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->type === 'company') {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized. Companies only.'], 403);
    }
}
