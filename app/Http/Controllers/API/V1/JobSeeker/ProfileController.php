<?php

namespace App\Http\Controllers\API\V1\JobSeeker;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobSeeker\StoreProfileRequest;
use App\Http\Requests\JobSeeker\UpdateProfileRequest;
use App\Models\JobSeekerProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function store(StoreProfileRequest $request) 
    {
        $data            = $request->validated();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('seekers/photos', 'public');
        }
        if ($request->hasFile('resume')) {
            $data['resume'] = $request->file('resume')->store('seekers/resumes', 'public');
        }

        $profile = JobSeekerProfile::create($data);

        return response()->json(['success' => true, 'message' => 'Profile created.', 'data' => $profile], 201);
    }

    public function show() 
    {
        $profile = JobSeekerProfile::where('user_id', Auth::id())
            ->withCount('applications')
            ->firstOrFail();

        return response()->json(['success' => true, 'data' => $profile]);
    }

    public function update(UpdateProfileRequest $request) 
    {
        $profile = JobSeekerProfile::where('user_id', Auth::id())->firstOrFail();
        $data    = $request->validated();

        if ($request->hasFile('profile_photo')) {
            if ($profile->profile_photo) Storage::disk('public')->delete($profile->profile_photo);
            $data['profile_photo'] = $request->file('profile_photo')->store('seekers/photos', 'public');
        }
        if ($request->hasFile('resume')) {
            if ($profile->resume) Storage::disk('public')->delete($profile->resume);
            $data['resume'] = $request->file('resume')->store('seekers/resumes', 'public');
        }

        $profile->update($data);

        return response()->json(['success' => true, 'message' => 'Profile updated.', 'data' => $profile->fresh()]);
    }
}