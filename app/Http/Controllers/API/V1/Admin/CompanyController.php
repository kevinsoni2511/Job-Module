<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request) 
    {
        $companies = Company::query()
            ->when($request->keyword, fn($q) => $q->search($request->keyword))
            ->when($request->status,  fn($q) => $q->where('status', $request->status))
            ->withCount(['jobPosts', 'activeJobPosts'])
            ->latest()
            ->paginate(20);

        return response()->json(['success' => true, 'data' => $companies]);
    }

    public function show(int $id) 
    {
        $company = Company::with('user')
            ->withCount(['jobPosts', 'activeJobPosts'])
            ->findOrFail($id);

        return response()->json(['success' => true, 'data' => $company]);
    }

    public function updateStatus(Request $request, int $id) 
    {
        $request->validate([
            'status' => 'required|in:pending,active,inactive,rejected',
        ]);

        $company = Company::findOrFail($id);
        $company->update([
            'status'      => $request->status,
            'verified_at' => $request->status === 'active' ? now() : null,
        ]);

        return response()->json(['success' => true, 'message' => 'Company status updated.', 'data' => $company->fresh()]);
    }

    public function destroy(int $id) 
    {
        Company::findOrFail($id)->delete();

        return response()->json(['success' => true, 'message' => 'Company deleted.']);
    }
}