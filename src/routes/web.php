<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* ========================================
   お問い合わせフォーム関連ルート
   ======================================== */

// お問い合わせフォーム入力ページ
Route::get('/', [ContactController::class, 'index'])->name('contact.index');

// お問い合わせフォーム確認ページ（POST: 入力画面からの送信 → セッションに保存 → 確認画面にリダイレクト）
Route::post('/store', [ContactController::class, 'store'])->name('contact.store');

// お問い合わせフォーム確認ページ（GET: 確認画面を表示）
Route::get('/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');

// お問い合わせフォーム修正処理（GET: 確認画面から修正ボタンで入力画面に戻る）
Route::get('/edit', [ContactController::class, 'edit'])->name('contact.edit');

// お問い合わせフォーム送信処理（POST: 確認画面からの送信）
Route::post('/confirm', [ContactController::class, 'send'])->name('contact.send');

// お問い合わせフォーム送信完了ページ
Route::get('/thanks', [ContactController::class, 'thanks'])->name('contact.thanks');

/* ========================================
   認証関連ルート
   ======================================== */
// Fortifyが自動的にルートを登録するため、以下のルートはコメントアウト

// // ユーザー登録画面（GET）
// Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('auth.register');

// // ユーザー登録処理（POST: 登録フォームからの送信）
// // FormRequest: UserRegisterRequest 適用（コントローラ側で処理）
// Route::post('/register', [AuthController::class, 'register'])->name('auth.register.post');

// // ログイン画面（GET）
// Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');

// // ログイン処理（POST: ログインフォームからの送信）
// // FormRequest: UserLoginRequest 適用（コントローラ側で処理）
// Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');

// // ログアウト処理
// Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

/* ========================================
   管理画面関連ルート
   ======================================== */

Route::middleware('auth')->group(function () {
    // 管理画面一覧
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    // 管理画面検索結果
    Route::get('/search', [AdminController::class, 'search'])->name('admin.search');

    // 管理画面検索条件リセット
    Route::get('/reset', [AdminController::class, 'reset'])->name('admin.reset');

    // お問い合わせ詳細データ取得（JSON形式）
    Route::get('/admin/{id}', [AdminController::class, 'show'])->name('admin.show');

    // お問い合わせデータ削除
    Route::post('/delete', [AdminController::class, 'delete'])->name('admin.delete');

    // CSVエクスポート
    Route::get('/export', [AdminController::class, 'export'])->name('admin.export');
});
