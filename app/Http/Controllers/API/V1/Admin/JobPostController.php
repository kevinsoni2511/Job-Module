<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobPostController extends Controller
{
    public function index(Request $request) 
    {
        $jobs = JobPost::with(['company:id,name', 'category:id,name'])
            ->when($request->keyword,    fn($q) => $q->search($request->keyword))
            ->when($request->status,     fn($q) => $q->where('status', $request->status))
            ->when($request->company_id, fn($q) => $q->where('company_id', $request->company_id))
            ->withCount('applications')
            ->latest()
            ->paginate(20);

        return response()->json(['success' => true, 'data' => $jobs]);
    }

    public function show(int $id) 
    {
        $job = JobPost::with(['company', 'category'])->withCount('applications')->findOrFail($id);

        return response()->json(['success' => true, 'data' => $job]);
    }

    public function updateStatus(Request $request, int $id) 
    {
        $request->validate(['status' => 'required|in:draft,active,closed,expired']);

        $job = JobPost::findOrFail($id);
        $job->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'Job status updated.', 'data' => $job->fresh()]);
    }

    public function destroy(int $id) 
    {
        JobPost::findOrFail($id)->delete();

        return response()->json(['success' => true, 'message' => 'Job post deleted.']);
    }
}