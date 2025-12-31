<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * ユーザー登録用バリデーションリクエスト
 * 
 * 適用先: ユーザー登録ページ (/register)
 * Controller: AuthController@register
 */
class UserRegisterRequest extends FormRequest
{
    /**
     * 認証が必要かどうか
     * 
     * @return bool
     */
    public function authorize()
    {
        // ユーザー登録は認証不要
        return true;
    }

    /**
     * バリデーションルール
     * 
     * @return array
     */
    public function rules()
    {
        return [
            // お名前: 必須
            'name' => ['required'],
            
            // メールアドレス: 必須、メール形式
            'email' => ['required', 'email'],
            
            // パスワード: 必須
            'password' => ['required'],
        ];
    }

    /**
     * エラーメッセージ
     * 
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'お名前は必須です',
            
            'email.required' => 'メールアドレスは必須です',
            'email.email' => 'メールアドレスの形式が正しくありません',
            
            'password.required' => 'パスワードは必須です',
        ];
    }
}

