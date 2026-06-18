<?php

namespace App\Http\Requests\JobKeeper;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = Auth::user()->company?->id;
        return [
            'name'         => "sometimes|string|max:255|unique:companies,name,{$id}",
            'email'        => "sometimes|email|max:255|unique:companies,email,{$id}",
            'phone'        => 'nullable|string|max:20',
            'website'      => 'nullable|url|max:255',
            'logo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'cover_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'industry'     => 'nullable|string|max:100',
            'company_size' => 'nullable|string|max:50',
            'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
            'description'  => 'nullable|string|max:5000',
            'address'      => 'nullable|string|max:500',
            'city'         => 'nullable|string|max:100',
            'state'        => 'nullable|string|max:100',
            'country'      => 'nullable|string|max:100',
            'pincode'      => 'nullable|string|max:20',
        ];
    }
}