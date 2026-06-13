@extends('layouts.app')

@section('title', '会員登録(一般ユーザー)')

@section('css')
<link rel="stylesheet" href="{{ asset('/css/auth.css')  }}">
@endsection

@section('content')
<div class="auth">
    <div class="auth__card">
        <h1 class="auth__title">会員登録</h1>
        <form action="{{ route('register') }}" method="post" class="form">
            @csrf
            <div class="auth__form">
                <label for="name" class="auth__label">お名前</label>
                <input type="text" id="name" name="name" class="auth__input" value="{{ old('name') }}">
                <div class="form__error">
                    @error('name')
                    {{ $message }}
                    @enderror
                </div>
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
                <label for="password_confirm" class="auth__label">確認用パスワード</label>
                <input type="password" id="password_confirm" name="password_confirm" class="auth__input">
                <div class="auth__action">
                    <button type="submit" class="auth__button">登録する</button>
                </div>
            </div>
            <a class="auth__link" href="{{ route('login') }}">ログインはこちら</a>
        </form>
    </div>
</div>
@endsection