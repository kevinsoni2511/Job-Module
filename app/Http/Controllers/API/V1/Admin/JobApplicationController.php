<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function index(Request $request) 
    {
        $applications = JobApplication::with(['user:id,name,email', 'jobPost.company:id,name'])
            ->when($request->status,      fn($q) => $q->where('status', $request->status))
            ->when($request->company_id,  fn($q) => $q->whereHas('jobPost', fn($q) => $q->where('company_id', $request->company_id)))
            ->when($request->job_post_id, fn($q) => $q->where('job_post_id', $request->job_post_id))
            ->latest()
            ->paginate(20);

        return response()->json(['success' => true, 'data' => $applications]);
    }

    public function show(int $id) 
    {
        $application = JobApplication::with(['user', 'jobPost.company', 'seekerProfile'])->findOrFail($id);

        return response()->json(['success' => true, 'data' => $application]);
    }

    public function destroy(int $id) 
    {
        JobApplication::findOrFail($id)->delete();

        return response()->json(['success' => true, 'message' => 'Application deleted.']);
    }
}