@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/export.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/modal.css') }}">
@endsection

@section('content')
    <div class="admin-container">
        <!-- ヘッダー -->
        <div class="admin-header">
            <h1 class="admin-title">Admin</h1>
            <form action="{{ route('auth.logout') }}" method="POST" class="admin-logout-form">
                @csrf
                <button type="submit" class="admin-logout-button">logout</button>
            </form>
        </div>

        <!-- 検索フォーム -->
        <div class="admin-search-section">
            <form action="{{ route('admin.search') }}" method="GET" class="admin-search-form">
                <div class="admin-search-row">
                    <input 
                        type="text" 
                        name="keyword" 
                        class="admin-search-input"
                        value="{{ request('keyword') }}"
                        placeholder="名前やメールアドレスを入力してください"
                    >
                    <select name="gender" class="admin-search-select">
                        <option value="">性別</option>
                        <option value="1" {{ request('gender') == '1' ? 'selected' : '' }}>男性</option>
                        <option value="2" {{ request('gender') == '2' ? 'selected' : '' }}>女性</option>
                        <option value="3" {{ request('gender') == '3' ? 'selected' : '' }}>その他</option>
                    </select>
                    <select name="category_id" class="admin-search-select">
                        <option value="">お問い合わせの種類</option>
                        @if(isset($categories))
                            @foreach($categories as $category)
                                <option 
                                    value="{{ $category->id }}"
                                    {{ request('category_id') == $category->id ? 'selected' : '' }}
                                >
                                    {{ $category->content }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <input 
                        type="date" 
                        name="date" 
                        class="admin-search-date"
                        value="{{ request('date') }}"
                    >
                    <button type="submit" class="admin-search-button">検索</button>
                    <a href="{{ route('admin.reset') }}" class="admin-reset-button">リセット</a>
                </div>
            </form>
        </div>

        <!-- エクスポートボタン -->
        <div class="admin-export-section">
            <form action="{{ route('admin.export') }}" method="GET" class="admin-export-form">
                @if(request('keyword'))
                    <input type="hidden" name="keyword" value="{{ request('keyword') }}">
                @endif
                @if(request('gender'))
                    <input type="hidden" name="gender" value="{{ request('gender') }}">
                @endif
                @if(request('category_id'))
                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                @endif
                @if(request('date'))
                    <input type="hidden" name="date" value="{{ request('date') }}">
                @endif
                <button type="submit" class="admin-export-button">エクスポート</button>
            </form>
        </div>

        <!-- 一覧テーブル -->
        <div class="admin-table-section">
            <table class="admin-table">
                <thead class="admin-table-head">
                    <tr>
                        <th class="admin-table-header">お名前</th>
                        <th class="admin-table-header">性別</th>
                        <th class="admin-table-header">メールアドレス</th>
                        <th class="admin-table-header">お問い合わせの種類</th>
                        <th class="admin-table-header">操作</th>
                    </tr>
                </thead>
                <tbody class="admin-table-body">
                    @if(isset($contacts) && $contacts->count() > 0)
                        @foreach($contacts as $contact)
                            <tr class="admin-table-row">
                                <td class="admin-table-cell">{{ $contact->last_name }} {{ $contact->first_name }}</td>
                                <td class="admin-table-cell">
                                    @if($contact->gender == 1)
                                        男性
                                    @elseif($contact->gender == 2)
                                        女性
                                    @elseif($contact->gender == 3)
                                        その他
                                    @endif
                                </td>
                                <td class="admin-table-cell">{{ $contact->email }}</td>
                                <td class="admin-table-cell">{{ $contact->category->content ?? '' }}</td>
                                <td class="admin-table-cell">
                                    <button 
                                        type="button" 
                                        class="admin-detail-button"
                                        data-contact-id="{{ $contact->id }}"
                                        data-modal-open="adminModal"
                                    >
                                        詳細
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="admin-table-row">
                            <td colspan="5" class="admin-table-no-data">データがありません</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <!-- ページネーション -->
        @if(isset($contacts) && $contacts->hasPages())
            <div class="admin-pagination-section">
                <div class="admin-pagination">
                    {{ $contacts->links('pagination::admin') }}
                </div>
            </div>
        @endif

        <!-- モーダル（詳細表示・削除用） -->
        <div class="admin-modal" id="adminModal">
            <div class="admin-modal-overlay" data-modal-close="adminModal"></div>
            <div class="admin-modal-content">
                <!-- モーダルヘッダー -->
                <div class="admin-modal-header">
                    <h2 class="admin-modal-title">お問い合わせ詳細</h2>
                    <button 
                        type="button" 
                        class="admin-modal-close" 
                        data-modal-close="adminModal"
                        aria-label="閉じる"
                    >
                        ×
                    </button>
                </div>
                
                <!-- モーダルボディ -->
                <div class="admin-modal-body">
                    <dl class="admin-modal-list">
                        <div class="admin-modal-item">
                            <dt class="admin-modal-label">お名前</dt>
                            <dd class="admin-modal-value" id="modal-name">-</dd>
                        </div>
                        <div class="admin-modal-item">
                            <dt class="admin-modal-label">性別</dt>
                            <dd class="admin-modal-value" id="modal-gender">-</dd>
                        </div>
                        <div class="admin-modal-item">
                            <dt class="admin-modal-label">メールアドレス</dt>
                            <dd class="admin-modal-value" id="modal-email">-</dd>
                        </div>
                        <div class="admin-modal-item">
                            <dt class="admin-modal-label">電話番号</dt>
                            <dd class="admin-modal-value" id="modal-tel">-</dd>
                        </div>
                        <div class="admin-modal-item">
                            <dt class="admin-modal-label">住所</dt>
                            <dd class="admin-modal-value" id="modal-address">-</dd>
                        </div>
                        <div class="admin-modal-item">
                            <dt class="admin-modal-label">建物名</dt>
                            <dd class="admin-modal-value" id="modal-building">-</dd>
                        </div>
                        <div class="admin-modal-item">
                            <dt class="admin-modal-label">お問い合わせの種類</dt>
                            <dd class="admin-modal-value" id="modal-category">-</dd>
                        </div>
                        <div class="admin-modal-item">
                            <dt class="admin-modal-label">お問い合わせ内容</dt>
                            <dd class="admin-modal-value admin-modal-detail" id="modal-detail">-</dd>
                        </div>
                    </dl>
                </div>
                
                <!-- モーダルフッター -->
                <div class="admin-modal-footer">
                    <form action="{{ route('admin.delete') }}" method="POST" class="admin-delete-form">
                        @csrf
                        <input type="hidden" name="contact_id" id="modal-contact-id" value="">
                        <button type="submit" class="admin-delete-button">削除</button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- モーダル制御用JavaScript -->
        @section('js')
        <script>
            // モーダルを開く（詳細ボタンクリック時）
            $(document).on('click', '[data-modal-open]', function() {
                var modalId = $(this).data('modal-open');
                var contactId = $(this).data('contact-id');
                
                // お問い合わせ詳細データを取得
                $.ajax({
                    url: '/admin/' + contactId,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // モーダルにデータを表示
                        $('#modal-name').text(data.last_name + ' ' + data.first_name);
                        $('#modal-gender').text(data.gender);
                        $('#modal-email').text(data.email);
                        $('#modal-tel').text(data.tel_part1 + '-' + data.tel_part2 + '-' + data.tel_part3);
                        $('#modal-address').text(data.address);
                        $('#modal-building').text(data.building);
                        $('#modal-category').text(data.category);
                        $('#modal-detail').text(data.detail);
                        
                        // 削除フォームのcontact_idを設定
                        $('#modal-contact-id').val(data.id);
                        
                        // モーダルを表示
                        $('#' + modalId).addClass('is-active');
                    },
                    error: function(xhr, status, error) {
                        alert('お問い合わせデータの取得に失敗しました');
                    }
                });
            });
            
            // モーダルを閉じる
            $(document).on('click', '[data-modal-close]', function() {
                var modalId = $(this).data('modal-close');
                $('#' + modalId).removeClass('is-active');
            });
        </script>
        @endsection
    </div>
@endsection


