<?php

namespace App\Http\Controllers\API\V1\JobSeeker;

use App\Http\Controllers\Controller;
use App\Models\JobSeekerEducation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EducationController extends Controller
{
    public function index() 
    {
        $education = JobSeekerEducation::where('user_id', Auth::id())
            ->orderBy('start_year', 'desc')
            ->get();

        return response()->json(['success' => true, 'data' => $education]);
    }

    public function store(Request $request) 
    {
        $request->validate([
            'degree'      => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'field'       => 'nullable|string|max:255',
            'start_year'  => 'required|integer|min:1990|max:' . date('Y'),
            'end_year'    => 'nullable|integer|min:1990|max:' . (date('Y') + 6),
            'is_current'  => 'boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        $education = JobSeekerEducation::create([
            'user_id'     => Auth::id(),
            'degree'      => $request->degree,
            'institution' => $request->institution,
            'field'       => $request->field,
            'start_year'  => $request->start_year,
            'end_year'    => $request->end_year,
            'is_current'  => $request->boolean('is_current', false),
            'description' => $request->description,
        ]);

        return response()->json(['success' => true, 'message' => 'Education added.', 'data' => $education], 201);
    }

    public function update(Request $request, int $id) 
    {
        $education = JobSeekerEducation::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'degree'      => 'sometimes|string|max:255',
            'institution' => 'sometimes|string|max:255',
            'field'       => 'nullable|string|max:255',
            'start_year'  => 'sometimes|integer|min:1990|max:' . date('Y'),
            'end_year'    => 'nullable|integer|min:1990|max:' . (date('Y') + 6),
            'is_current'  => 'boolean',
            'description' => 'nullable|string|max:1000',
        ]);

        $education->update($request->only(['degree', 'institution', 'field', 'start_year', 'end_year', 'is_current', 'description']));

        return response()->json(['success' => true, 'message' => 'Education updated.', 'data' => $education->fresh()]);
    }

    public function destroy(int $id) 
    {
        JobSeekerEducation::where('id', $id)->where('user_id', Auth::id())->firstOrFail()->delete();

        return response()->json(['success' => true, 'message' => 'Education deleted.']);
    }
}