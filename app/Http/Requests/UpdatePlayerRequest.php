<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdatePlayerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('email') && is_string($this->input('email'))) {
            $this->merge([
                'email' => strtolower($this->input('email')),
            ]);
        }
    }

    public function rules(): array
    {
        $routePlayer = $this->route('player') ?? $this->route('id');

        $playerId = $routePlayer instanceof \App\Models\User
            ? $routePlayer->id
            : $routePlayer;

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
                'nullable',
                'confirmed',
                Password::defaults(),
            ],
        ];
    }
}