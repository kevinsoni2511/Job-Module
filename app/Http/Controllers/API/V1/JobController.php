<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobController extends Controller
{
    /**
     * GET /api/v1/jobs
     * Query params: keyword, category, city, job_type, work_mode, experience
     */
    public function index(Request $request): JsonResponse
    {
        $jobs = JobPost::active()
            ->with(['company:id,name,logo,city', 'category:id,name'])
            ->when($request->keyword,    fn($q) => $q->where(function ($q) use ($request) {
                $q->where('title',       'like', '%' . $request->keyword . '%')
                  ->orWhere('description','like', '%' . $request->keyword . '%')
                  ->orWhere('city',       'like', '%' . $request->keyword . '%');
            }))
            ->when($request->category,   fn($q) => $q->where('job_category_id', $request->category))
            ->when($request->city,       fn($q) => $q->where('city', 'like', '%' . $request->city . '%'))
            ->when($request->job_type,   fn($q) => $q->where('job_type', $request->job_type))
            ->when($request->work_mode,  fn($q) => $q->where('work_mode', $request->work_mode))
            ->when($request->experience, fn($q) => $q->where('experience_level', $request->experience))
            ->latest('published_at')
            ->paginate(15);

        return response()->json(['success' => true, 'data' => $jobs]);
    }

    /**
     * GET /api/v1/jobs/{id}
     */
    public function show(int $id): JsonResponse
    {
        $job = JobPost::active()
            ->with(['company', 'category'])
            ->findOrFail($id);

        $job->incrementViews();

        return response()->json(['success' => true, 'data' => $job]);
    }
}