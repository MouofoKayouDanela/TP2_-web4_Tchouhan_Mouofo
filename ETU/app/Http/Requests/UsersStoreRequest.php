<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{   
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
           'login' => 'required|string|max:50|unique:users',
           'password' => 'required|string|min:8|max:255',
            'email' => 'required|string|email|max:50|unique:users',            
            'last_name' => 'nullable|string|max:50',
            'first_name' => 'nullable|string|max:50',
        ];
    }
}
