<?php

namespace App\Http\Requests\JobKeeper;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobPostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'job_category_id'      => 'nullable|exists:job_categories,id',
            'title'                => 'sometimes|string|max:255',
            'description'          => 'sometimes|string|max:10000',
            'requirements'         => 'nullable|string|max:5000',
            'responsibilities'     => 'nullable|string|max:5000',
            'benefits'             => 'nullable|string|max:5000',
            'job_type'             => 'nullable|in:full-time,part-time,contract,internship',
            'work_mode'            => 'nullable|in:remote,on-site,hybrid',
            'experience_level'     => 'nullable|in:entry,mid,senior,lead,executive',
            'experience_min_years' => 'nullable|integer|min:0|max:50',
            'experience_max_years' => 'nullable|integer|min:0|max:50|gte:experience_min_years',
            'salary_min'           => 'nullable|numeric|min:0',
            'salary_max'           => 'nullable|numeric|min:0|gte:salary_min',
            'salary_currency'      => 'nullable|string|max:10',
            'is_salary_visible'    => 'boolean',
            'city'                 => 'nullable|string|max:100',
            'state'                => 'nullable|string|max:100',
            'country'              => 'nullable|string|max:100',
            'vacancies'            => 'nullable|integer|min:1|max:9999',
            'application_deadline' => 'nullable|date|after:today',
            'status'               => 'sometimes|in:draft,active,closed',
        ];
    }
}