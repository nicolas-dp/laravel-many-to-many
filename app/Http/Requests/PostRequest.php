<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Post;
class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       /*  return [
            'title' => ['required', Rule::unique('posts')->ignore($post), 'max:150'],
            'cover_image' => ['nullable'],
            'content' => ['nullable']
        ]; */

        return [
            'title' => ['required', 'unique:posts', 'max:150'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'cover_image' => ['nullable'],
            'content' => ['nullable']
        ];
    }
}

