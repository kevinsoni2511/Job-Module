<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'name'      => "sometimes|string|max:255|unique:job_categories,name,{$id}",
            'is_active' => 'boolean',
        ];
    }
}