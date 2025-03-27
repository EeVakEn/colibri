<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SkillsActivateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('content')->channel->user->id === Auth::id();
    }

    public function rules(): array
    {
        return [
            'skill_ids' => 'required|array',
            'skill_ids.*' => 'integer|exists:skills,id',
        ];
    }
}
