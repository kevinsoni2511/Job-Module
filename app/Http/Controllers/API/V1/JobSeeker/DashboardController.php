<?php

namespace App\Http\Controllers\API\V1\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobSeekerProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index() 
    {
        $userId  = Auth::id();
        $profile = JobSeekerProfile::where('user_id', $userId)->first();

        $stats = [
            'total_applications' => JobApplication::where('user_id', $userId)->count(),
            'shortlisted'        => JobApplication::where('user_id', $userId)->where('status', 'shortlisted')->count(),
            'rejected'           => JobApplication::where('user_id', $userId)->where('status', 'rejected')->count(),
            'hired'              => JobApplication::where('user_id', $userId)->where('status', 'hired')->count(),
            'saved_jobs'         => DB::table('saved_jobs')->where('user_id', $userId)->count(),
        ];

        $recentApplications = JobApplication::where('user_id', $userId)
            ->with(['jobPost.company:id,name'])
            ->latest()->take(5)->get();

        return response()->json([
            'success' => true,
            'data'    => [
                'profile'             => $profile,
                'stats'               => $stats,
                'recent_applications' => $recentApplications,
            ],
        ]);
    }
}