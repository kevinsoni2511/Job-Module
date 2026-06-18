<?php

namespace App\Http\Requests\JobSeeker;

use Illuminate\Foundation\Http\FormRequest;

class ApplyJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_seeker_profile_id' => 'nullable|exists:job_seeker_profiles,id',
            'cover_letter'          => 'nullable|string|max:5000',
            'resume'                => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ];
    }
}