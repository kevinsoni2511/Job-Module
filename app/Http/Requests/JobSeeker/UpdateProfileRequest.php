<?php

namespace App\Http\Requests\JobSeeker;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name'              => 'sometimes|string|max:255',
            'phone'                  => 'nullable|string|max:20',
            'profile_photo'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'resume'                 => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'date_of_birth'          => 'nullable|date|before:today',
            'gender'                 => 'nullable|in:male,female,other,prefer_not_to_say',
            'current_job_title'      => 'nullable|string|max:255',
            'current_company'        => 'nullable|string|max:255',
            'total_experience_years' => 'nullable|integer|min:0|max:60',
            'current_salary'         => 'nullable|numeric|min:0',
            'expected_salary'        => 'nullable|numeric|min:0',
            'bio'                    => 'nullable|string|max:3000',
            'skills'                 => 'nullable|string|max:1000',
            'education'              => 'nullable|string|max:1000',
            'city'                   => 'nullable|string|max:100',
            'state'                  => 'nullable|string|max:100',
            'country'                => 'nullable|string|max:100',
            'linkedin_url'           => 'nullable|url|max:255',
            'portfolio_url'          => 'nullable|url|max:255',
            'is_actively_looking'    => 'boolean',
            'is_profile_visible'     => 'boolean',
        ];
    }
}