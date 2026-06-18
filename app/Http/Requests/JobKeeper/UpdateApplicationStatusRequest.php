<?php

namespace App\Http\Requests\JobKeeper;

use Illuminate\Foundation\Http\FormRequest;

class UpdateApplicationStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status'          => 'required|in:under_review,shortlisted,interview_scheduled,rejected,hired',
            'recruiter_notes' => 'nullable|string|max:2000',
        ];
    }
}