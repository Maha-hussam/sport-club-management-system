<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMatchPlayersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'players' => ['sometimes', 'array'],

            'players.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('users', 'id')
                    ->where('role', 'player'),
            ],
        ];
    }
}