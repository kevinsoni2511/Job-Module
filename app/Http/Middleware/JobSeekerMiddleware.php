<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JobSeekerMiddleware
{               
    public function handle(Request $request, Closure $next): mixed
    {
        if (! $request->user() || $request->user()->role !== 'job_seeker') {
            return response()->json(['success' => false, 'message' => 'Access denied. Job Seekers only.'], 403);
        }

        return $next($request);
    }
}