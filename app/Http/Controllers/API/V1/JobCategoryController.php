<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use Illuminate\Http\JsonResponse;

class JobCategoryController extends Controller
{
    public function index() 
    {
        $categories = JobCategory::active()
            ->withCount('jobPosts')
            ->orderBy('name')
            ->get();

        return response()->json(['success' => true, 'data' => $categories]);
    }

    public function show() 
    {
        $category = JobCategory::where('is_active', true)
            ->firstOrFail();

        return response()->json(['success' => true, 'data' => $category]);
    }
}