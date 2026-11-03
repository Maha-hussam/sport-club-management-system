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
        // Keep the original field name for each endpoint.
        $field = $this->has('players') ? 'players' : 'player_ids';

        return [
            $field => ['sometimes', 'array'],

            "{$field}.*" => [
                'required',
                'integer',
                'distinct',
                Rule::exists('users', 'id')
                    ->where('role', 'player'),
            ],
        ];
    }
}
