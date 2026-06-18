<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\JobSeekerProfile;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index() 
    {
        $stats = [
            'total_users'        => User::count(),
            'total_companies'    => Company::count(),
            'active_companies'   => Company::where('status', 'active')->count(),
            'pending_companies'  => Company::where('status', 'pending')->count(),
            'total_jobs'         => JobPost::count(),
            'active_jobs'        => JobPost::where('status', 'active')->count(),
            'total_applications' => JobApplication::count(),
            'total_seekers'      => JobSeekerProfile::count(),
        ];

        $recentCompanies = Company::latest()->take(5)->get(['id', 'name', 'status', 'created_at']);
        $recentJobs      = JobPost::with('company:id,name')->latest()->take(5)->get(['id', 'title', 'status', 'company_id', 'created_at']);

        return response()->json([
            'success' => true,
            'data'    => [
                'stats'           => $stats,
                'recent_companies'=> $recentCompanies,
                'recent_jobs'     => $recentJobs,
            ],
        ]);
    }
}