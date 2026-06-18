<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        return [
            'name'     => 'sometimes|string|max:255',
            'email'    => "sometimes|email|unique:users,email,{$id}",
            'role'     => 'sometimes|in:job_keeper,job_seeker,admin',
            'password' => 'sometimes|string|min:8|confirmed',
        ];
    }
}