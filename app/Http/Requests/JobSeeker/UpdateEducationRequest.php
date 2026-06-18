<?php

namespace App\Http\Requests\JobSeeker;

use Illuminate\Foundation\Http\FormRequest;

class StoreEducationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'degree'      => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'field'       => 'nullable|string|max:255',
            'start_year'  => 'required|integer|min:1990|max:' . date('Y'),
            'end_year'    => 'nullable|integer|min:1990|max:' . (date('Y') + 6),
            'is_current'  => 'boolean',
            'description' => 'nullable|string|max:1000',
        ];
    }
}