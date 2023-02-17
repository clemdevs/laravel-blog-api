<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'string|max:255',
            'body' => 'string|min:10',
            'user_id' => 'exists:users,id',
            'categories' => 'string|exists:categories,name',
            'tags' => 'string|max:255',
            'image' => 'nullable|image|mimes:png,jpeg,jpg,svg,webp|max:2048'
        ];
    }
}
