<?php

namespace App\Http\Requests\JobSeeker;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_title'   => 'sometimes|string|max:255',
            'company'     => 'sometimes|string|max:255',
            'location'    => 'nullable|string|max:255',
            'start_date'  => 'sometimes|date',
            'end_date'    => 'nullable|date|after:start_date',
            'is_current'  => 'boolean',
            'description' => 'nullable|string|max:2000',
        ];
    }
}