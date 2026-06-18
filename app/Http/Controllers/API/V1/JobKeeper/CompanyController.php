<?php

namespace App\Http\Controllers\API\V1\JobKeeper;

use App\Http\Controllers\Controller;
use App\Http\Requests\JobKeeper\StoreCompanyRequest;
use App\Http\Requests\JobKeeper\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function store(StoreCompanyRequest $request):JsonResponse 
    {
        $data            = $request->validated();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('companies/logos', 'public');
        }
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('companies/covers', 'public');
        }

        $company = Company::create($data);

        return response()->json(['success' => true, 'message' => 'Company registered.', 'data' => $company], 201);
    }

    public function show() 
    {
        $company = Company::where('user_id', Auth::id())
            ->withCount(['jobPosts', 'activeJobPosts'])
            ->firstOrFail();

        return response()->json(['success' => true, 'data' => $company]);
    }

    public function update(UpdateCompanyRequest $request) 
    {
        $company = Company::where('user_id', Auth::id())->firstOrFail();
        $data    = $request->validated();

        if ($request->hasFile('logo')) {
            if ($company->logo) Storage::disk('public')->delete($company->logo);
            $data['logo'] = $request->file('logo')->store('companies/logos', 'public');
        }
        if ($request->hasFile('cover_image')) {
            if ($company->cover_image) Storage::disk('public')->delete($company->cover_image);
            $data['cover_image'] = $request->file('cover_image')->store('companies/covers', 'public');
        }

        $company->update($data);

        return response()->json(['success' => true, 'message' => 'Company updated.', 'data' => $company->fresh()]);
    }
}