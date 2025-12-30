<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Contact;

/**
 * 管理画面用コントローラ
 * 
 * 管理画面の一覧・検索・削除・エクスポート機能を管理します。
 */
class AdminController extends Controller
{
    /**
     * 管理画面一覧を表示
     * 
     * URL: /admin
     * ルート名: admin.index
     * Blade: admin/index.blade.php
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // お問い合わせの種類（categories）を取得してBladeに渡す
        $categories = Category::all();
        
        // お問い合わせデータを取得（ページネーション対応、カテゴリも一緒に取得）
        $contacts = Contact::with('category')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('admin.index', compact('categories', 'contacts'));
    }

    /**
     * 管理画面検索結果を表示
     * 
     * URL: /search
     * ルート名: admin.search
     * Blade: admin/search.blade.php
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function search(Request $request)
    {
        // お問い合わせの種類（categories）を取得してBladeに渡す
        $categories = Category::all();
        
        // 検索条件を取得
        $gender = $request->input('gender');
        $categoryId = $request->input('category_id');
        $createdFrom = $request->input('created_from');
        $createdTo = $request->input('created_to');
        $email = $request->input('email');
        
        // 検索条件に基づいてお問い合わせデータを取得（ページネーション対応）
        $query = Contact::with('category');
        
        // 性別でフィルタ
        if (!empty($gender)) {
            $query->where('gender', $gender);
        }
        
        // お問い合わせの種類でフィルタ
        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }
        
        // 作成日（開始）でフィルタ
        if (!empty($createdFrom)) {
            $query->whereDate('created_at', '>=', $createdFrom);
        }
        
        // 作成日（終了）でフィルタ
        if (!empty($createdTo)) {
            $query->whereDate('created_at', '<=', $createdTo);
        }
        
        // メールアドレスでフィルタ（部分一致）
        if (!empty($email)) {
            $query->where('email', 'like', '%' . $email . '%');
        }
        
        // 作成日時の降順でソート
        $contacts = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.search', compact('categories', 'contacts'));
    }

    /**
     * 管理画面検索条件をリセットして一覧にリダイレクト
     * 
     * URL: /reset
     * ルート名: admin.reset
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset()
    {
        // TODO: 検索条件をクリア
        
        return redirect()->route('admin.index');
    }

    /**
     * お問い合わせ詳細データを取得（JSON形式）
     * 
     * URL: /admin/{id}
     * ルート名: admin.show
     * HTTPメソッド: GET
     * 
     * @param int $id お問い合わせID
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        // お問い合わせデータを取得（カテゴリも一緒に取得）
        $contact = Contact::with('category')->find($id);
        
        // データが存在しない場合
        if (!$contact) {
            return response()->json(['error' => 'お問い合わせデータが見つかりません'], 404);
        }
        
        // 性別の表示用テキストを設定
        $genderText = '';
        if ($contact->gender == 1) {
            $genderText = '男性';
        } elseif ($contact->gender == 2) {
            $genderText = '女性';
        } elseif ($contact->gender == 3) {
            $genderText = 'その他';
        }
        
        // JSON形式で返す
        return response()->json([
            'id' => $contact->id,
            'last_name' => $contact->last_name,
            'first_name' => $contact->first_name,
            'gender' => $genderText,
            'email' => $contact->email,
            'tel_part1' => $contact->tel_part1,
            'tel_part2' => $contact->tel_part2,
            'tel_part3' => $contact->tel_part3,
            'address' => $contact->address,
            'building' => $contact->building ?: '（未入力）',
            'category' => $contact->category->content ?? '',
            'detail' => $contact->detail,
        ]);
    }

    /**
     * お問い合わせデータを削除
     * 
     * URL: /delete
     * ルート名: admin.delete
     * HTTPメソッド: POST
     * 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        // バリデーション
        $request->validate([
            'contact_id' => ['required', 'integer', 'exists:contacts,id'],
        ]);
        
        // お問い合わせデータを取得
        $contact = Contact::find($request->input('contact_id'));
        
        // データが存在する場合のみ削除
        if ($contact) {
            $contact->delete();
        }
        
        return redirect()->route('admin.index')->with('message', '削除しました');
    }

    /**
     * CSVエクスポート処理
     * 
     * URL: /export
     * ルート名: admin.export
     * HTTPメソッド: GET
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request)
    {
        // TODO: 検索条件を取得
        // TODO: 検索条件に基づいてお問い合わせデータを取得
        // TODO: CSVファイルを生成してダウンロード
        
        // 一旦、リダイレクト（後でCSVダウンロード処理に変更）
        return redirect()->route('admin.index');
    }
}

