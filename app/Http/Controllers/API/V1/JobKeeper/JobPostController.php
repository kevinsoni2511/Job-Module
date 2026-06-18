<?php

namespace App\Http\Controllers\API\V1\JobKeeper;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobKeeper\StoreJobPostRequest;
use App\Http\Requests\JobKeeper\UpdateJobPostRequest;

use App\Models\Company;
use App\Models\JobPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobPostController extends Controller
{
    public function index(Request $request) 
    {
        $company = Company::where('user_id', Auth::id())->firstOrFail();

        $jobs = JobPost::where('company_id', $company->id)
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->with('category:id,name')
            ->withCount('applications')
            ->latest()
            ->paginate(15);

        return response()->json(['success' => true, 'data' => $jobs]);
    }

    public function store(StoreJobPostRequest $request) 
    {
        $company        = Company::where('user_id', Auth::id())->firstOrFail();
        $data           = $request->validated();
        $data['company_id'] = $company->id;

        if ($request->boolean('publish_now')) {
            $data['status']       = 'active';
            $data['published_at'] = now();
        }

        $job = JobPost::create($data);

        return response()->json(['success' => true, 'message' => 'Job post created.', 'data' => $job->load('category')], 201);
    }

    public function show(int $id) 
    {
        $company = Company::where('user_id', Auth::id())->firstOrFail();

        $job = JobPost::where('id', $id)->where('company_id', $company->id)
            ->withCount('applications')->with('category')->firstOrFail();

        return response()->json(['success' => true, 'data' => $job]);
    }

    public function update(UpdateJobPostRequest $request, int $id) 
    {
        $company = Company::where('user_id', Auth::id())->firstOrFail();

        $job = JobPost::where('id', $id)->where('company_id', $company->id)->firstOrFail();
        $job->update($request->validated());

        return response()->json(['success' => true, 'message' => 'Job post updated.', 'data' => $job->fresh()]);
    }

    public function toggleStatus(int $id) 
    {
        $company = Company::where('user_id', Auth::id())->firstOrFail();
        $job     = JobPost::where('id', $id)->where('company_id', $company->id)->firstOrFail();

        $job->status === 'active' ? $job->close() : $job->publish();

        return response()->json(['success' => true, 'message' => 'Status updated.', 'data' => $job->fresh()]);
    }

    public function destroy(int $id) 
    {
        $company = Company::where('user_id', Auth::id())->firstOrFail();
        JobPost::where('id', $id)->where('company_id', $company->id)->firstOrFail()->delete();

        return response()->json(['success' => true, 'message' => 'Job post deleted.']);
    }
}