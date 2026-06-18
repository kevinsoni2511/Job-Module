<?php

namespace App\Http\Requests\JobSeeker;

use Illuminate\Foundation\Http\FormRequest;

class StoreExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_title'   => 'required|string|max:255',
            'company'     => 'required|string|max:255',
            'location'    => 'nullable|string|max:255',
            'start_date'  => 'required|date',
            'end_date'    => 'nullable|date|after:start_date',
            'is_current'  => 'boolean',
            'description' => 'nullable|string|max:2000',
        ];
    }
}