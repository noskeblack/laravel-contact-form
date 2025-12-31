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
            ->paginate(7);
        
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
        $keyword = $request->input('keyword');
        $gender = $request->input('gender');
        $categoryId = $request->input('category_id');
        $createdFrom = $request->input('created_from');
        $createdTo = $request->input('created_to');
        $searchType = $request->input('search_type', 'partial'); // デフォルトは部分一致
        
        // 検索条件に基づいてお問い合わせデータを取得（ページネーション対応）
        $query = Contact::with('category');
        
        // 名前・メールアドレスでフィルタ
        if (!empty($keyword)) {
            if ($searchType === 'exact') {
                // 完全一致
                $query->where(function($q) use ($keyword) {
                    $q->where('last_name', $keyword)
                      ->orWhere('first_name', $keyword)
                      ->orWhere('email', $keyword);
                });
            } else {
                // 部分一致（デフォルト）
                $query->where(function($q) use ($keyword) {
                    $q->where('last_name', 'like', '%' . $keyword . '%')
                      ->orWhere('first_name', 'like', '%' . $keyword . '%')
                      ->orWhere('email', 'like', '%' . $keyword . '%');
                });
            }
        }
        
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
        
        // 作成日時の降順でソート
        $contacts = $query->orderBy('created_at', 'desc')->paginate(7);
        
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
        // 検索条件を取得（search()メソッドと同じロジック）
        $keyword = $request->input('keyword');
        $gender = $request->input('gender');
        $categoryId = $request->input('category_id');
        $createdFrom = $request->input('created_from');
        $createdTo = $request->input('created_to');
        $searchType = $request->input('search_type', 'partial');
        
        // 検索条件に基づいてお問い合わせデータを取得
        $query = Contact::with('category');
        
        // 名前・メールアドレスでフィルタ
        if (!empty($keyword)) {
            if ($searchType === 'exact') {
                // 完全一致
                $query->where(function($q) use ($keyword) {
                    $q->where('last_name', $keyword)
                      ->orWhere('first_name', $keyword)
                      ->orWhere('email', $keyword);
                });
            } else {
                // 部分一致（デフォルト）
                $query->where(function($q) use ($keyword) {
                    $q->where('last_name', 'like', '%' . $keyword . '%')
                      ->orWhere('first_name', 'like', '%' . $keyword . '%')
                      ->orWhere('email', 'like', '%' . $keyword . '%');
                });
            }
        }
        
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
        
        // 作成日時の降順でソート
        $contacts = $query->orderBy('created_at', 'desc')->get();
        
        // CSVファイルを生成してダウンロード
        $filename = 'contacts_' . date('YmdHis') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($contacts) {
            $file = fopen('php://output', 'w');
            
            // BOMを追加（Excelで文字化けしないように）
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // ヘッダー行
            fputcsv($file, [
                'ID',
                'お名前（姓）',
                'お名前（名）',
                '性別',
                'メールアドレス',
                '電話番号',
                '住所',
                '建物名',
                'お問い合わせの種類',
                'お問い合わせ内容',
                '作成日時',
            ]);
            
            // データ行
            foreach ($contacts as $contact) {
                // 性別の表示用テキスト
                $genderText = '';
                if ($contact->gender == 1) {
                    $genderText = '男性';
                } elseif ($contact->gender == 2) {
                    $genderText = '女性';
                } elseif ($contact->gender == 3) {
                    $genderText = 'その他';
                }
                
                // 電話番号（ハイフンなし）
                $tel = $contact->tel_part1 . $contact->tel_part2 . $contact->tel_part3;
                
                fputcsv($file, [
                    $contact->id,
                    $contact->last_name,
                    $contact->first_name,
                    $genderText,
                    $contact->email,
                    $tel,
                    $contact->address,
                    $contact->building ?? '',
                    $contact->category->content ?? '',
                    $contact->detail,
                    $contact->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}

