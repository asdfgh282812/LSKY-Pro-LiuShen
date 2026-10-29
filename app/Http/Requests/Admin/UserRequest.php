<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rules;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'group_id' => 'required',
            'name' => 'required|between:2,30',
            'capacity' => 'required|numeric',
            'password' => ['nullable', Rules\Password::defaults()],
            'status' => 'in:1,0'
        ];
    }

    public function attributes()
    {
        return [
            'group_id' => '角色組',
            'name' => '暱稱',
            'capacity' => '總容量',
            'password' => '密碼',
            'status' => '狀態',
        ];
    }
}
