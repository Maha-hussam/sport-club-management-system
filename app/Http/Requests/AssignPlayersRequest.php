<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignPlayersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'player_ids' => ['present', 'array'],

            'player_ids.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('users', 'id')
                    ->where('role', 'player'),
            ],
        ];
    }
}