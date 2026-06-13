<header class="header">
    <div class="header__inner">
        <div class=" header__logo">
            <a href="/"><img src="{{ asset('img/COACHTECHヘッダーロゴ.png') }}" alt="ロゴ"></a>
        </div>
        @auth
        <nav class="header__nav">
            <ul class="header__nav-list">
                @if(Auth::user()->is_admin)
                <li class="header__nav-item"><a href="#">勤怠一覧</a></li>
                <li class="header__nav-item"><a href="#">スタッフ一覧</a></li>
                <li class="header__nav-item"><a href="#">申請一覧</a></li>
                @else
                <li class="header__nav-item"><a href="#">勤怠</a></li>
                <li class="header__nav-item"><a href="#">勤怠一覧</a></li>
                <li class="header__nav-item"><a href="#">申請</a></li>
                @endif
                <li class="header__nav-item">
                    <form action="{{ route('logout') }}" method="post" class="header__logout">
                        @csrf
                        <button type="submit" class="header__logout-btn">ログアウト</button>
                    </form>
                </li>
            </ul>
        </nav>
        @endauth
    </div>
</header>