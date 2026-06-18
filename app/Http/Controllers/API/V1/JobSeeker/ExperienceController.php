<?php

namespace App\Http\Controllers\API\V1\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\JobSeekerExperience;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExperienceController extends Controller
{
    public function index() 
    {
        $experience = JobSeekerExperience::where('user_id', Auth::id())
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $experience]);
    }

    public function store(Request $request) 
    {
        $request->validate([
            'job_title'   => 'required|string|max:255',
            'company'     => 'required|string|max:255',
            'location'    => 'nullable|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after:start_date',
            'is_current'  => 'boolean',
            'description' => 'nullable|string|max:2000',
        ]);

        $experience = JobSeekerExperience::create([
            'user_id'     => Auth::id(),
            'job_title'   => $request->job_title,
            'company'     => $request->company,
            'location'    => $request->location,
            'start_date'  => $request->start_date,
            'end_date'    => $request->end_date,
            'is_current'  => $request->boolean('is_current', false),
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'message' => 'Experience added.', 'data' => $experience], 201);
    }

    public function update(Request $request, int $id) 
    {
        $experience = JobSeekerExperience::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'job_title'   => 'sometimes|string|max:255',
            'company'     => 'sometimes|string|max:255',
            'location'    => 'nullable|string|max:255',
            'start_date'  => 'sometimes|date',
            'end_date'    => 'nullable|date|after:start_date',
            'is_current'  => 'boolean',
            'description' => 'nullable|string|max:2000',
        ]);

        $experience->update($request->only(['job_title', 'company', 'location', 'start_date', 'end_date', 'is_current', 'description']));

        return response()->json(['success' => true, 'message' => 'Experience updated.', 'data' => $experience->fresh()]);
    }

    public function destroy(int $id) 
    {
        JobSeekerExperience::where('id', $id)->where('user_id', Auth::id())->firstOrFail()->delete();

        return response()->json(['success' => true, 'message' => 'Experience deleted.']);
    }
}