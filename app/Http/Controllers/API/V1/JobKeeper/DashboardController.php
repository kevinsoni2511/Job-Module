<?php

namespace App\Http\Controllers\API\V1\JobKeeper;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() 
    {
        $company = Company::where('user_id', Auth::id())->firstOrFail();

        $stats = [
            'total_jobs'         => JobPost::where('company_id', $company->id)->count(),
            'active_jobs'        => JobPost::where('company_id', $company->id)->where('status', 'active')->count(),
            'closed_jobs'        => JobPost::where('company_id', $company->id)->where('status', 'closed')->count(),
            'total_applications' => JobApplication::whereHas('jobPost', fn($q) => $q->where('company_id', $company->id))->count(),
            'shortlisted'        => JobApplication::whereHas('jobPost', fn($q) => $q->where('company_id', $company->id))->where('status', 'shortlisted')->count(),
            'hired'              => JobApplication::whereHas('jobPost', fn($q) => $q->where('company_id', $company->id))->where('status', 'hired')->count(),
        ];

        $recentApplications = JobApplication::whereHas('jobPost', fn($q) => $q->where('company_id', $company->id))
            ->with(['user:id,name,email', 'jobPost:id,title'])
            ->latest()->take(5)->get();

        return response()->json([
            'success' => true,
            'data'    => [
                'company'             => $company,
                'stats'               => $stats,
                'recent_applications' => $recentApplications,
            ],
        ]);
    }
}