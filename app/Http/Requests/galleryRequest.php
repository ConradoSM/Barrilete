<?php

namespace barrilete\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class galleryRequest extends FormRequest
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
            'title' => 'required|min:20|max:191|unique:gallery,title,'.$this->input('id'),
            'section_id' => 'required',
            'author' => 'required',          
            'article_desc' => 'required|min:50'
        ];
    }
}
