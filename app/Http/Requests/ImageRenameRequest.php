<?php

namespace App\Http\Requests;

class ImageRenameRequest extends FormRequest
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
            'id' => 'required|numeric',
            'name' => 'required|max:50|string',
        ];
    }

    public function messages()
    {
        return [
            'id.required' => '請選擇一張圖片',
            'id.numeric' => '圖片選擇異常',
            'name.required' => '請輸入名稱',
            'name.max' => '名稱長度不能超過 50 個字元',
            'name.string' => '名稱格式不正確',
        ];
    }
}
