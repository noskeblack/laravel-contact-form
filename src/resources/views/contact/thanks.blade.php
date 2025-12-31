@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/contact/thanks.css') }}">
@endsection

@section('content')
    <div class="thanks-container">
        <div class="thanks-message">
            <p class="thanks-text">お問い合わせありがとうございました</p>
        </div>
        <div class="thanks-button">
            <a href="{{ route('contact.index') }}" class="btn-home">HOME</a>
        </div>
    </div>
@endsection

