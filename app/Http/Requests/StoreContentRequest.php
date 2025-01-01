<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => 'required|string|in:image,video',
            'title' => 'required|string',
            'content' => 'nullable|string',
            'preview' => request()->method() == 'PUT'
                ? 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:1024'
                : 'required|image|mimes:jpeg,png,jpg,gif,svg|max:1024',
            'video' => 'nullable|mimes:mp4,ogx,oga,ogv,ogg,webm',
        ];
    }
}
