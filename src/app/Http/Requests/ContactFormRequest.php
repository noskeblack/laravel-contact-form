<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * お問い合わせフォーム用バリデーションリクエスト
 */
class ContactFormRequest extends FormRequest
{
    /**
     * 認証が必要かどうか
     * 
     * @return bool
     */
    public function authorize()
    {
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
            'last_name' => ['required', 'string', 'max:8'],
            'first_name' => ['required', 'string', 'max:8'],
            'gender' => ['required'],
            'email' => ['required', 'email'],
            'tel_part1' => ['required', 'regex:/^[0-9a-zA-Z]+$/', 'max:5'],
            'tel_part2' => ['required', 'regex:/^[0-9a-zA-Z]+$/', 'max:5'],
            'tel_part3' => ['required', 'regex:/^[0-9a-zA-Z]+$/', 'max:5'],
            'address' => ['required'],
            'category_id' => ['required', 'exists:categories,id'],
            'detail' => ['required', 'max:120'],
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
            'last_name.required' => '姓を入力してください',
            'last_name.string' => '姓は文字列で入力してください',
            'last_name.max' => '姓は最大8文字まで入力可能です',
            
            'first_name.required' => '名を入力してください',
            'first_name.string' => '名は文字列で入力してください',
            'first_name.max' => '名は最大8文字まで入力可能です',
            
            'gender.required' => '性別を選択してください',
            
            'email.required' => 'メールアドレスを入力してください',
            'email.email' => 'メールアドレスはメール形式で入力してください',
            
            'tel_part1.required' => '電話番号を入力してください',
            'tel_part1.regex' => '電話番号は半角英数字で入力してください',
            'tel_part1.max' => '電話番号は5桁まで数字で入力してください',
            
            'tel_part2.required' => '電話番号を入力してください',
            'tel_part2.regex' => '電話番号は半角英数字で入力してください',
            'tel_part2.max' => '電話番号は5桁まで数字で入力してください',
            
            'tel_part3.required' => '電話番号を入力してください',
            'tel_part3.regex' => '電話番号は半角英数字で入力してください',
            'tel_part3.max' => '電話番号は5桁まで数字で入力してください',
            
            'address.required' => '住所を入力してください',
            
            'category_id.required' => 'お問い合わせの種類を選択してください',
            'category_id.exists' => '選択されたお問い合わせの種類は無効です',
            
            'detail.required' => 'お問い合わせ内容を入力してください',
            'detail.max' => 'お問い合わせ内容は120文字以内で入力してください',
        ];
    }
}

