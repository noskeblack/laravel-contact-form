<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\Category;
use App\Models\Contact;

/**
 * お問い合わせフォーム用コントローラ
 */
class ContactController extends Controller
{
    /**
     * お問い合わせフォーム入力画面を表示
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = Category::all();
        
        return view('contact.index', compact('categories'));
    }

    /**
     * お問い合わせフォーム入力データをセッションに保存して確認画面にリダイレクト
     * 
     * @param ContactFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ContactFormRequest $request)
    {
        $validated = $request->validated();
        session()->put('contact_data', $validated);
        
        return redirect()->route('contact.confirm');
    }

    /**
     * お問い合わせフォーム確認画面を表示
     * 
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function confirm()
    {
        if (!session()->has('contact_data')) {
            return redirect()->route('contact.index');
        }
        
        $categories = Category::all();
        
        return view('contact.confirm', compact('categories'));
    }

    /**
     * お問い合わせフォーム修正処理（確認画面から入力画面に戻る）
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit()
    {
        $contactData = session('contact_data', []);
        
        return redirect()->route('contact.index')->withInput($contactData);
    }

    /**
     * お問い合わせフォーム送信処理
     * 
     * @param ContactFormRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function send(ContactFormRequest $request)
    {
        $validated = $request->validated();
        
        $contactData = [
            'last_name' => $validated['last_name'],
            'first_name' => $validated['first_name'],
            'gender' => (int)$validated['gender'],
            'email' => $validated['email'],
            'tel_part1' => $validated['tel_part1'],
            'tel_part2' => $validated['tel_part2'],
            'tel_part3' => $validated['tel_part3'],
            'address' => $validated['address'],
            'building' => !empty($validated['building']) ? $validated['building'] : null,
            'category_id' => (int)$validated['category_id'],
            'detail' => $validated['detail'],
        ];
        
        Contact::create($contactData);
        session()->forget('contact_data');
        
        return redirect()->route('contact.thanks');
    }

    /**
     * お問い合わせフォーム送信完了画面を表示
     * 
     * @return \Illuminate\View\View
     */
    public function thanks()
    {
        return view('contact.thanks');
    }
}

