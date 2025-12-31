<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * ユーザーログイン用バリデーションリクエスト
 * 
 * 適用先: ログインページ (/login)
 * Controller: AuthController@login
 * 
 * 注意: 要件では「お名前: required」とありますが、
 * 実際のBladeファイル（auth/login.blade.php）には
 * お名前フィールドが存在しないため、メールアドレスのみをバリデーションします。
 */
class UserLoginRequest extends FormRequest
{
    /**
     * 認証が必要かどうか
     * 
     * @return bool
     */
    public function authorize()
    {
        // ログインは認証不要
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
            // メールアドレス: 必須、メール形式
            'email' => ['required', 'email'],
            
            // パスワード: 必須（ログインにはパスワードが必要）
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
            'email.required' => 'メールアドレスは必須です',
            'email.email' => 'メールアドレスの形式が正しくありません',
            
            'password.required' => 'パスワードは必須です',
        ];
    }
}

