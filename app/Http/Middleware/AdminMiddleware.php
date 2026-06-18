<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (! $request->user() || $request->user()->role !== 'admin') {
            return response()->json([
                    'success' => false, 'message' =>'Access denied. Admins only.'], 403);
        }

        return $next($request);
    }
}