<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required | string | min:2',
            'email' => 'required | string | email | unique:users',
            'password' => 'required | confirmed',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名前を入力してください',
            'name.string' => '文字列で入力してください',
            'name.min' => '２文字以上で入力してください',
            'email.required' => 'メールアドレスを入力してください',
            'email.string' => '文字列で入力してください',
            'email.email' => 'メール形式で入力してください',
            'email.unique' => 'そのメールアドレスは登録済みです',
            'password.required' => 'パスワードを入力してください',
            'password.confirmed' => '確認用パスワードと一致しません',
        ];
    }
}
