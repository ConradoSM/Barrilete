<?php

namespace barrilete\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class userRequest extends FormRequest
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
            'name' => 'required|unique:users,name,'.$this->input('id'),
            'email' => 'required|email|unique:users,email,'.$this->input('id'),
            'birthday' => 'date',          
            'phone' => 'max:20',
            'photo' => request()->has('id') ? '' : 'image|mimes:jpeg,png,jpg|max:1024'
        ];
    }
}
