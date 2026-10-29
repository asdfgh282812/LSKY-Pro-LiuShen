<?php

namespace App\Http\Requests;

class UserSettingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|between:2,20',
            'url' => 'nullable|url',
            'password' => 'nullable|between:6,32',
            'configs' => 'array',
            'configs.default_album' => 'required|numeric',
            'configs.default_strategy' => 'required|numeric',
            'configs.default_permission' => 'required|in:1,0',
            'configs.pasted_action' => 'required|in:1,2',
            'configs.is_auto_clear_preview' => 'nullable|boolean'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '暱稱不能為空',
            'name.between' => '暱稱必須在 2-20 個字元之間',
            'url.url' => '個人主頁位址格式不正確',
            'password.between' => '密碼必須在 6-32 個字元之間',
            'configs.array' => '設定值不正確',
            'configs.default_album.required' => '預設相簿選擇錯誤',
            'configs.default_album.numeric' => '預設相簿選擇錯誤',
            'configs.default_strategy.required' => '預設策略選擇錯誤',
            'configs.default_strategy.numeric' => '預設策略選擇錯誤',
            'configs.default_permission.required' => '權限值選擇錯誤',
            'configs.default_permission.in' => '權限值不正確',
            'configs.pasted_action.required' => '貼上動作值選擇錯誤',
            'configs.pasted_action.in' => '貼上動作值不正確',
            'configs.is_auto_clear_preview.boolean' => '是否自動清除預覽選擇錯誤'
        ];
    }
}
