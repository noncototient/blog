<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:255',
            'body' => 'required|max:500',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Post title field is required',
            'title.max' => 'Post title character length cannot be longer than 255 characters',
            'body.required' => 'Post body field is required',
            'body.max' => 'Post body length cannot be longer than 500 characters',
        ];
    }
}
