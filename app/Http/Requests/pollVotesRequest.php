<?php

namespace barrilete\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class pollVotesRequest extends FormRequest
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
            'poll_id' => 'required',
            'option' => 'required'
        ];
    }
}
