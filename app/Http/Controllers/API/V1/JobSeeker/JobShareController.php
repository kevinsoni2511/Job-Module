<?php

namespace App\Http\Controllers\API\V1\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\JsonResponse;

class JobShareController extends Controller
{
    public function share(int $id) 
    {
        $job = JobPost::active()->with('company:id,name')->findOrFail($id);

        $shareData = [
            'title'       => $job->title,
            'company'     => $job->company->name,
            'location'    => $job->city . ', ' . $job->state,
            'job_type'    => $job->job_type,
            'work_mode'   => $job->work_mode,
            'url'         => url('/jobs/' . $job->slug),
            'description' => str(strip_tags($job->description))->limit(200)->toString(),
        ];

        return response()->json(['success' => true, 'data' => $shareData]);
    }
}