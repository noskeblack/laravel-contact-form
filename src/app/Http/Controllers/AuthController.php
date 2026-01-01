<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// UserRegisterRequest を適用
use App\Http\Requests\UserRegisterRequest;
// UserLoginRequest を適用
use App\Http\Requests\UserLoginRequest;

/**
 * 認証用コントローラ
 * 
 * ユーザー登録・ログイン・ログアウト機能を管理します。
 */
class AuthController extends Controller
{
    /**
     * ユーザー登録画面を表示
     * 
     * URL: /register
     * ルート名: auth.register
     * HTTPメソッド: GET
     * Blade: auth/register.blade.php
     * 
     * @return \Illuminate\View\View
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * ユーザー登録処理
     * 
     * URL: /register
     * ルート名: auth.register.post
     * HTTPメソッド: POST
     * 
     * UserRegisterRequest を適用:
     * - バリデーションルールが自動的に実行される
     * - バリデーション失敗時は自動的に前のページにリダイレクト
     * - バリデーション成功時のみこのメソッドが実行される
     * 
     * @param UserRegisterRequest $request バリデーション済みリクエスト
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(UserRegisterRequest $request)
    {
        // UserRegisterRequest によりバリデーション済みデータを取得
        $validated = $request->validated();
        
        // ユーザー登録処理（パスワードはハッシュ化）
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        
        // ログイン処理
        Auth::login($user);
        
        return redirect()->route('admin.index');
    }

    /**
     * ログイン画面を表示
     * 
     * URL: /login
     * ルート名: auth.login
     * HTTPメソッド: GET
     * Blade: auth/login.blade.php
     * 
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * ログイン処理
     * 
     * URL: /login
     * ルート名: auth.login.post
     * HTTPメソッド: POST
     * 
     * UserLoginRequest を適用:
     * - バリデーションルールが自動的に実行される
     * - バリデーション失敗時は自動的に前のページにリダイレクト
     * - バリデーション成功時のみこのメソッドが実行される
     * 
     * @param UserLoginRequest $request バリデーション済みリクエスト
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(UserLoginRequest $request)
    {
        // UserLoginRequest によりバリデーション済みデータを取得
        $validated = $request->validated();
        
        // ログイン認証処理
        $credentials = [
            'email' => $validated['email'],
            'password' => $validated['password'],
        ];
        
        $remember = $request->filled('remember');
        
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->route('admin.index');
        }
        
        // 認証失敗時はバリデーションエラーとして返す
        return back()->withErrors([
            'email' => 'メールアドレスまたはパスワードが正しくありません。',
        ])->withInput($request->only('email'));
    }

    /**
     * ログアウト処理
     * 
     * URL: /logout
     * ルート名: auth.logout
     * HTTPメソッド: POST
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // ログアウト処理
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}

