<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request) 
    {
        $companies = Company::active()
            ->when($request->keyword,  fn($q) => $q->search($request->keyword))
            ->when($request->industry, fn($q) => $q->where('industry', $request->industry))
            ->when($request->city,     fn($q) => $q->where('city', 'like', '%' . $request->city . '%'))
            ->withCount('activeJobPosts')
            ->paginate(15);

        return response()->json(['success' => true, 'data' => $companies]);
    }

    public function show(string $slug) 
    {
        $company = Company::where('slug', $slug)
            ->where('status', 'active')
            ->withCount('activeJobPosts')
            ->firstOrFail();

        return response()->json(['success' => true, 'data' => $company]);
    }
}