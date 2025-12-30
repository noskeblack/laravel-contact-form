<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// ContactFormRequest を適用
use App\Http\Requests\ContactFormRequest;
use App\Models\Category;
use App\Models\Contact;

/**
 * お問い合わせフォーム用コントローラ
 * 
 * お問い合わせフォームの入力・確認・完了画面を管理します。
 * 
 * FormRequest適用状況:
 * - send() メソッド: ContactFormRequest を適用済み
 *   → ルーティング: Route::post('/confirm', ...) でPOSTリクエスト時にバリデーションが自動実行される
 */
class ContactController extends Controller
{
    /**
     * お問い合わせフォーム入力画面を表示
     * 
     * URL: /
     * ルート名: contact.index
     * Blade: contact/index.blade.php
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // お問い合わせの種類（categories）を取得してBladeに渡す
        $categories = Category::all();
        
        return view('contact.index', compact('categories'));
    }

    /**
     * お問い合わせフォーム入力データをセッションに保存して確認画面にリダイレクト
     * 
     * URL: /store
     * ルート名: contact.store
     * HTTPメソッド: POST（入力画面からの送信）
     * 
     * ContactFormRequest を適用:
     * - バリデーションルールが自動的に実行される
     * - バリデーション失敗時は自動的に前のページにリダイレクト
     * - バリデーション成功時のみこのメソッドが実行される
     * 
     * @param ContactFormRequest $request バリデーション済みリクエスト
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ContactFormRequest $request)
    {
        // ContactFormRequest によりバリデーション済みデータを取得
        $validated = $request->validated();
        
        // セッションにデータを保存（確認画面で表示するため）
        session()->put('contact_data', $validated);
        
        // 確認画面にリダイレクト
        return redirect()->route('contact.confirm');
    }

    /**
     * お問い合わせフォーム確認画面を表示
     * 
     * URL: /confirm
     * ルート名: contact.confirm
     * HTTPメソッド: GET
     * Blade: contact/confirm.blade.php
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function confirm()
    {
        // セッションからデータを取得（入力画面からPOSTされたデータ）
        // セッションにデータが存在しない場合は入力画面にリダイレクト
        if (!session()->has('contact_data')) {
            return redirect()->route('contact.index');
        }
        
        // お問い合わせの種類（categories）を取得してBladeに渡す
        $categories = Category::all();
        
        return view('contact.confirm', compact('categories'));
    }

    /**
     * お問い合わせフォーム送信処理
     * 
     * URL: /confirm
     * ルート名: contact.send
     * HTTPメソッド: POST（確認画面からの送信）
     * 
     * ContactFormRequest を適用:
     * - バリデーションルールが自動的に実行される
     * - バリデーション失敗時は自動的に前のページにリダイレクト
     * - バリデーション成功時のみこのメソッドが実行される
     * 
     * @param ContactFormRequest $request バリデーション済みリクエスト
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(ContactFormRequest $request)
    {
        // ContactFormRequest によりバリデーション済みデータを取得
        $validated = $request->validated();
        
        // データ型を適切に変換
        $data = [
            'last_name' => $validated['last_name'],
            'first_name' => $validated['first_name'],
            'gender' => (int)$validated['gender'], // 文字列から整数に変換
            'email' => $validated['email'],
            'tel_part1' => $validated['tel_part1'],
            'tel_part2' => $validated['tel_part2'],
            'tel_part3' => $validated['tel_part3'],
            'address' => $validated['address'],
            'building' => !empty($validated['building']) ? $validated['building'] : null, // 空文字列を null に変換
            'category_id' => (int)$validated['category_id'], // 文字列から整数に変換
            'detail' => $validated['detail'],
        ];
        
        // contacts テーブルに保存
        Contact::create($data);
        
        // セッションからデータを削除
        session()->forget('contact_data');
        
        // 送信完了画面にリダイレクト
        return redirect()->route('contact.thanks');
    }

    /**
     * お問い合わせフォーム送信完了画面を表示
     * 
     * URL: /thanks
     * ルート名: contact.thanks
     * HTTPメソッド: GET
     * Blade: contact/thanks.blade.php
     * 
     * @return \Illuminate\View\View
     */
    public function thanks()
    {
        return view('contact.thanks');
    }
}

