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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'string|max:1000', //TODO: this max:1000 is not corresponding with database field varchar(255)
            'body' => 'nullable|string',
            'user_id' => 'exists:users,id',
            'categories_id' => 'nullable|array',
            'tags' => 'nullable|array',
            'image' => 'image|nullable|mimes:png,jpeg,jpg,svg,webp|max:2048'
        ];
    }
}
