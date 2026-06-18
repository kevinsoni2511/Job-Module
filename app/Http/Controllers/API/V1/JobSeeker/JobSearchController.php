<?php

namespace App\Http\Controllers\API\V1\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobSearchController extends Controller
{
    public function index(Request $request) 
    {
        $jobs = JobPost::active()
            ->with(['company:id,name,logo,city', 'category:id,name'])
            ->when($request->keyword,    fn($q) => $q->search($request->keyword))
            ->when($request->category,   fn($q) => $q->where('job_category_id', $request->category))
            ->when($request->city,       fn($q) => $q->where('city', 'like', '%' . $request->city . '%'))
            ->when($request->job_type,   fn($q) => $q->where('job_type', $request->job_type))
            ->when($request->work_mode,  fn($q) => $q->where('work_mode', $request->work_mode))
            ->when($request->experience, fn($q) => $q->where('experience_level', $request->experience))
            ->latest('published_at')
            ->paginate(15);

        return response()->json(['success' => true, 'data' => $jobs]);
    }

    public function show(string $slug) 
    {
        $job = JobPost::where('slug', $slug)
            ->active()
            ->with(['company', 'category'])
            ->firstOrFail();

        $job->incrementViews();

        return response()->json(['success' => true, 'data' => $job]);
    }
}