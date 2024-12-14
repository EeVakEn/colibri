<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChannelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'is_free' => 'nullable|boolean',
            'subscription_price' => 'nullable|numeric|min:0|max:100',
        ];
    }
}
