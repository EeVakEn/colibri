<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WithdrawRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'min:0.01', function ($attribute, $value, $fail) {
                if ($value > auth()->user()->balance) {
                    $fail('Insufficient funds. Your current balance is ' . auth()->user()->balance . '.');
                }
            }],
        ];
    }
}
