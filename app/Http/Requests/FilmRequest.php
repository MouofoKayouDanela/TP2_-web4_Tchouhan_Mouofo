<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
            return [
            'title' => 'required|string|max:50',
            'release_year' => 'required|integer|min:1900|max:' . date('Y'),
            'length' => 'required|integer|min:1|max:999',
            'description' => 'required|string',
            'rating' => 'required|string|max:5',
            'special_features' => 'required|string|max:200',
            'image' => 'required|string|max:40',
            'language_id' => 'required|exists:languages,id',
        ];
    }
}
