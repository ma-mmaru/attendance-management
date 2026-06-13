@extends('layouts.app')

@section('title', 'ログイン画面')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/auth.css')  }}">
@endsection

@section('content')
<div class="auth">
    <div class="auth__card">
        <h1 class="auth__title">{{ request()->is('admin*') ? '管理者ログイン' : 'ログイン' }}</h1>
        <form action="{{ route('login') }}" method="post" class="form">
            @csrf
            <div class="auth__form">
                <label for="email" class="auth__label">メールアドレス</label>
                <input type="text" id="email" name="email" class="auth__input" value="{{ old('email') }}">
                <div class="form__error">
                    @error('email')
                    {{ $message }}
                    @enderror
                </div>
                <label for="password" class="auth__label">パスワード</label>
                <input type="password" id="password" name="password" class="auth__input">
                <div class="form__error">
                    @error('password')
                    {{ $message }}
                    @enderror
                </div>
                <div class="auth__action">
                    <button type="submit" class="auth__button">ログインする</button>
                </div>
            </div>
            @if(!request()->is('admin*'))
            <a class="auth__link" href="{{ route('register') }}">会員登録はこちら</a>
            @endif
        </form>
    </div>
</div>
@endsection