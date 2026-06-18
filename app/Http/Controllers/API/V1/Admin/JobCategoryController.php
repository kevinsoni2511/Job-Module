<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{
    public function index() 
    {
        $categories = JobCategory::withCount('jobPosts')->orderBy('name')->get();

        return response()->json(['success' => true, 'data' => $categories]);
    }

    public function store(Request $request) 
    {
        $request->validate([
            'name'      => 'required|string|max:255|unique:job_categories,name',
            'icon'      => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $category = JobCategory::create([
            'name'      => $request->name,
            'slug'      => strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $request->name), '-')),
            'icon'      => $request->icon,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return response()->json(['success' => true, 'message' => 'Category created.', 'data' => $category], 201);
    }

    public function update(Request $request, int $id) 
    {
        $category = JobCategory::findOrFail($id);

        $request->validate([
            'name'      => "sometimes|string|max:255|unique:job_categories,name,{$id}",
            'icon'      => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'icon', 'is_active']);
        if ($request->filled('name')) {
            $data['slug'] = strtolower(trim(preg_replace('/[^A-Za-z0-9]+/', '-', $request->name), '-'));
        }

        $category->update($data);

        return response()->json(['success' => true, 'message' => 'Category updated.', 'data' => $category->fresh()]);
    }

    public function destroy(int $id) 
    {
        JobCategory::findOrFail($id)->delete();

        return response()->json(['success' => true, 'message' => 'Category deleted.']);
    }
}