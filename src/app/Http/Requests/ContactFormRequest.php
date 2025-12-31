<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * お問い合わせフォーム用バリデーションリクエスト
 * 
 * 適用先: お問い合わせフォーム入力ページ (/)
 * Controller: ContactController@confirm
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
        // お問い合わせフォームは認証不要
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
            // お名前（姓）: 必須、文字列、最大8文字
            'last_name' => ['required', 'string', 'max:8'],
            
            // お名前（名）: 必須、文字列、最大8文字
            'first_name' => ['required', 'string', 'max:8'],
            
            // 性別: 必須
            'gender' => ['required'],
            
            // メールアドレス: 必須、メール形式
            'email' => ['required', 'email'],
            
            // 電話番号（3分割入力）: 必須、半角英数字、各項目最大5桁
            'tel_part1' => ['required', 'regex:/^[0-9a-zA-Z]+$/', 'max:5'],
            'tel_part2' => ['required', 'regex:/^[0-9a-zA-Z]+$/', 'max:5'],
            'tel_part3' => ['required', 'regex:/^[0-9a-zA-Z]+$/', 'max:5'],
            
            // 住所: 必須
            'address' => ['required'],
            
            // お問い合わせの種類: 必須、categoriesテーブルに存在するIDであること
            'category_id' => ['required', 'exists:categories,id'],
            
            // お問い合わせ内容: 必須、最大120文字
            'detail' => ['required', 'max:120'],
        ];
    }

    /**
     * カスタムバリデーション
     * 
     * 電話番号の各項目が最大5桁であることをチェック
     * （各項目の桁数チェックは rules() の max:5 で実装）
     * 
     * @param Validator $validator
     * @return void
     */
    public function withValidator(Validator $validator)
    {
        // 各項目が5桁までなので、合計チェックは不要
        // 各項目の桁数チェックは rules() の max:5 で実装済み
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

