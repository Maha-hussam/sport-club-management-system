<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'trainer';
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('match_name') && is_string($this->input('match_name'))) {
            $this->merge([
                'match_name' => trim($this->input('match_name')),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'match_name' => ['sometimes', 'required', 'string', 'max:255'],
            'match_date' => ['sometimes', 'required', 'date'],
            'match_time' => ['sometimes', 'required', 'date_format:H:i'],
            'location' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:2000'],
        ];
    }
}