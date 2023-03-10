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
            'title' => 'required|string|min:5|max:255',
            'body' => 'required|string|min:10',
            'user_id' => 'exists:users,id',
            'categories' => 'nullable|string|min:3',
            'tags' => 'nullable|string|max:255',
            'image' => 'nullable|image|nullable|mimes:png,jpeg,jpg,svg,webp|max:2048',
        ];
    }
}
