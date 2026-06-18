<?php

namespace App\Http\Controllers\API\V1\JobKeeper;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\JobPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function index(int $jobPostId, Request $request) 
    {
        $company = Company::where('user_id', Auth::id())->firstOrFail();
        JobPost::where('id', $jobPostId)->where('company_id', $company->id)->firstOrFail();

        $applications = JobApplication::where('job_post_id', $jobPostId)
            ->with(['user:id,name,email', 'seekerProfile'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->paginate(20);

        return response()->json(['success' => true, 'data' => $applications]);
    }

    public function show(int $jobPostId, int $id) 
    {
        $company = Company::where('user_id', Auth::id())->firstOrFail();
        JobPost::where('id', $jobPostId)->where('company_id', $company->id)->firstOrFail();

        $application = JobApplication::where('id', $id)->where('job_post_id', $jobPostId)
            ->with(['user', 'seekerProfile', 'jobPost'])->firstOrFail();

        return response()->json(['success' => true, 'data' => $application]);
    }

    public function updateStatus(Request $request, int $jobPostId, int $id) 
    {
        $request->validate([
            'status'          => 'required|in:under_review,shortlisted,interview_scheduled,rejected,hired',
            'recruiter_notes' => 'nullable|string|max:2000',
        ]);

        $company = Company::where('user_id', Auth::id())->firstOrFail();
        JobPost::where('id', $jobPostId)->where('company_id', $company->id)->firstOrFail();

        $application = JobApplication::where('id', $id)->where('job_post_id', $jobPostId)->firstOrFail();
        $application->updateStatus($request->status, $request->recruiter_notes);

        return response()->json(['success' => true, 'message' => 'Status updated.', 'data' => $application->fresh()]);
    }

    public function summary(int $jobPostId) 
    {
        $company = Company::where('user_id', Auth::id())->firstOrFail();
        JobPost::where('id', $jobPostId)->where('company_id', $company->id)->firstOrFail();

        $summary = JobApplication::where('job_post_id', $jobPostId)
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return response()->json(['success' => true, 'data' => $summary]);
    }
}