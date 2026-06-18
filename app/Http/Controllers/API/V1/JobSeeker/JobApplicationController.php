<?php

namespace App\Http\Controllers\API\V1\JobSeeker;

use App\Http\Controllers\Controller;

use App\Http\Requests\JobSeeker\ApplyJobRequest;
use App\Models\JobApplication;
use App\Models\JobPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    public function apply(ApplyJobRequest $request, int $jobPostId) 
    {
        JobPost::active()->findOrFail($jobPostId);

        $exists = JobApplication::where('job_post_id', $jobPostId)->where('user_id', Auth::id())->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'You have already applied for this job.'], 422);
        }

        $data                = $request->validated();
        $data['job_post_id'] = $jobPostId;
        $data['user_id']     = Auth::id();

        if ($request->hasFile('resume')) {
            $data['resume'] = $request->file('resume')->store('applications/resumes', 'public');
        }

        $application = JobApplication::create($data);

        return response()->json(['success' => true, 'message' => 'Application submitted.', 'data' => $application], 201);
    }

    public function index(Request $request) 
    {
        $applications = JobApplication::where('user_id', Auth::id())
            ->with(['jobPost.company:id,name', 'jobPost.category:id,name'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->paginate(15);

        return response()->json(['success' => true, 'data' => $applications]);
    }

    public function show(int $id) 
    {
        $application = JobApplication::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['jobPost.company'])
            ->firstOrFail();

        return response()->json(['success' => true, 'data' => $application]);
    }

    public function withdraw(int $id) 
    {
        $application = JobApplication::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if (! in_array($application->status, ['applied', 'under_review'])) {
            return response()->json(['success' => false, 'message' => 'Cannot withdraw at this stage.'], 422);
        }

        $application->updateStatus('withdrawn');

        return response()->json(['success' => true, 'message' => 'Application withdrawn.']);
    }
}