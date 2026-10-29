<?php

namespace App\Http\Requests;

class AlbumRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:60|alpha_dash',
            'intro' => 'max:600'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名稱不能為空',
            'name.max' => '名稱字元過長，最大不能超過 60',
            'name.alpha_dash' => '名稱只能是字母、數字，短破折號（-）和底線（_）',
            'intro.max' => '簡介字元過長，最大不能超過 600'
        ];
    }
}
