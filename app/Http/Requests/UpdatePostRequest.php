<?php

namespace App\Http\Requests;

use App\Models\Category;
use App\Models\Post;
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

    public function prepareForValidation(): void
    {
        $this->merge([
            'category_id' => $this->category_id,
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
            'title' => 'string|max:1000',
            'body' => 'nullable|string',
            'user_id' => 'exists:users,id',
            'categories' => 'exists:categories,id',
            'tags' => 'exists:tags,id',
            'image_url' => 'image|nullable|mimes:png,jpeg,jpg,svg,webp|max:2048'
        ];
    }
}
