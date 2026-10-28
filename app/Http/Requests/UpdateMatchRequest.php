<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'trainer';
    }

    public function rules(): array
    {
        return [
            'match_name' => ['sometimes', 'required', 'string', 'max:255'],
            'match_date' => ['sometimes', 'required', 'date'],
            'match_time' => ['sometimes', 'required', 'date_format:H:i'],
            'location' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
        ];
    }

}