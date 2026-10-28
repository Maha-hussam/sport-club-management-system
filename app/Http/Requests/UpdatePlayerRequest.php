<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdatePlayerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'trainer';
    }

    public function rules(): array
    {
        $playerId = $this->route('id');

        return [
            'first_name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'last_name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'sometimes',
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($playerId),
            ],

            'password' => [
                'sometimes',
                'required',
                'confirmed',
                Password::defaults(),
            ],
        ];
    }
}