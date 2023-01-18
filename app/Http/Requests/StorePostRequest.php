<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => $this->user()->id,
            'category_id' => $this->categories_id,
            'tags' => $this->tags
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|min:5|max:1000',
            'body' => 'required|string',
            'user_id' => 'exists:users,id',
            'category_id' => 'array|nullable|exists:categories,id',
            'tags' => 'array|nullable',
            'image' => 'image|nullable|mimes:png,jpeg,jpg,svg,webp|max:2048'
        ];
    }
}
