<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignPlayersRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'trainer';
    }

    public function rules(): array
    {
        return [
            'player_ids' => ['required', 'array', 'min:1'],
            'player_ids.*' => [
                'required',
                'integer',
                'distinct',
                'exists:users,id',
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $playerIds = $this->input('player_ids', []);

            if (!is_array($playerIds)) {
                return;
            }

            $nonPlayerExists = \App\Models\User::whereIn('id', $playerIds)
                ->where('role', '!=', 'player')
                ->exists();

            if ($nonPlayerExists) {
                $validator->errors()->add(
                    'player_ids',
                    'All selected users must be players.'
                );
            }
        });
    }
}