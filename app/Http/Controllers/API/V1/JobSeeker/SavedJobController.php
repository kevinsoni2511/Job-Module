<?php

namespace App\Http\Controllers\API\V1\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SavedJobController extends Controller
{
    public function index() 
    {
        $jobs = JobPost::active()
            ->whereHas('savedByUsers', fn($q) => $q->where('users.id', Auth::id()))
            ->with(['company:id,name,logo', 'category:id,name'])
            ->paginate(15);

        return response()->json(['success' => true, 'data' => $jobs]);
    }

    public function toggle(int $jobPostId) 
    {
        JobPost::active()->findOrFail($jobPostId);

        $exists = DB::table('saved_jobs')
            ->where('user_id', Auth::id())
            ->where('job_post_id', $jobPostId)
            ->exists();

        if ($exists) {
            DB::table('saved_jobs')->where('user_id', Auth::id())->where('job_post_id', $jobPostId)->delete();
            return response()->json(['success' => true, 'message' => 'Job removed from saved list.', 'saved' => false]);
        }

        DB::table('saved_jobs')->insert([
            'user_id'     => Auth::id(),
            'job_post_id' => $jobPostId,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Job saved.', 'saved' => true]);
    }
}