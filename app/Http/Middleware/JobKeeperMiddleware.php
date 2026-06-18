<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JobKeeperMiddleware
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (! $request->user() || $request->user()->role !== 'job_keeper') {
            return response()->json(['success' => false, 'message' => 'Access denied. Job Keepers only.'], 403);
        }

        return $next($request);
    }
}