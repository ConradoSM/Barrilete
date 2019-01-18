<?php

namespace barrilete\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class articleRequest extends FormRequest
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
        return [
            'user_id' => 'required',
            'title' => 'required|min:20|max:191|unique:articles,title,'.$this->input('id'),
            'section_id' => 'required',
            'author' => 'required',          
            'article_desc' => 'required|min:50',
            'photo' => request()->has('id') ? 'image|mimes:jpeg,png,jpg|max:2048' : 'required|image|mimes:jpeg,png,jpg|max:2048',
            'article_body' => 'required'
        ];
    }
}
