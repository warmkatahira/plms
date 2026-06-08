<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- jQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

        <!-- favicon -->
        <link rel="shortcut icon" href="{{ asset('image/favicon.svg') }}">

        <!-- Styles -->
        @vite([
            'resources/css/app.css',
            'resources/sass/theme.scss',
            'resources/sass/common.scss',
            'resources/sass/welcome.scss',
        ])

        <!-- Scripts -->
        @vite([
            'resources/js/app.js',
        ])

        <!-- LINE AWESOME -->
        <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Kosugi+Maru&family=Oleo+Script:wght@400;700&family=Zen+Maru+Gothic:wght@900&display=swap" rel="stylesheet">

        <!-- Lordicon -->
        <script src="https://cdn.lordicon.com/pzdvqjsp.js"></script>

        <!-- toastr.js -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

        <!-- Tippy.js -->
        <script src="https://unpkg.com/@popperjs/core@2"></script>
        <script src="https://unpkg.com/tippy.js@6"></script>

        <style>
            *, *::before, *::after { box-sizing: border-box; }

            /* ===== PC レイアウト ===== */
            html, body { height: 100%; margin: 0; padding: 0; }

            /* display は Tailwind（hidden / md:flex）に任せる */
            .wl-page {
                background: #FAFAF8;
                height: 100vh;          /* ← 100vh固定でスクロールなし */
                flex-direction: column;
                overflow: hidden;       /* ← はみ出し防止 */
            }

            .wl-nav {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 16px 48px;     /* ← 20px→16px に縮小 */
                border-bottom: 0.5px solid #E8E6E1;
                background: #FAFAF8;
                flex-shrink: 0;         /* ← ナビが縮まないよう固定 */
            }
            .wl-nav-logo {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .wl-nav-logo-mark {
                width: 32px;
                height: 32px;
                background: #1C1C1A;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .wl-nav-logo-mark i { font-size: 17px; color: #fff; }
            .wl-nav-logo-text {
                font-size: 24px;
                font-weight: 600;
                color: #1C1C1A;
                letter-spacing: -0.01em;
            }
            .wl-nav-login {
                background: #1C1C1A;
                color: #fff;
                border: none;
                border-radius: 8px;
                padding: 9px 24px;
                font-size: 13px;
                font-weight: 500;
                text-decoration: none;
                display: inline-block;
                transition: opacity 0.15s, transform 0.1s;
            }
            .wl-nav-login:hover { opacity: 0.75; color: #fff; }
            .wl-nav-login:active { transform: scale(0.97); }

            /* ヒーロー：残りの高さをすべて使う */
            .wl-hero {
                flex: 1;
                display: flex;
                align-items: stretch;
                min-height: 0;          /* ← flex子要素がはみ出さないよう必須 */
            }
            .wl-hero-left {
                flex: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
                padding: 40px 64px 40px 80px;   /* ← 64px→40px に縮小 */
                max-width: 580px;
            }
            .wl-h1 {
                font-size: 36px;        /* ← 40px→36px に縮小 */
                font-weight: 700;
                color: #1C1C1A;
                line-height: 1.25;
                letter-spacing: -0.03em;
                margin-bottom: 14px;
                animation: wlUp 0.5s ease 0.08s both;
            }
            .wl-h1 em { font-style: normal; color: var(--theme-main, #E8800A); }
            .wl-sub {
                font-size: 14px;        /* ← 15px→14px に縮小 */
                color: #888780;
                line-height: 1.8;
                margin-bottom: 28px;    /* ← 36px→28px に縮小 */
                animation: wlUp 0.5s ease 0.16s both;
            }
            .wl-cta {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: var(--theme-main, #E8800A);
                color: #fff;
                border: none;
                border-radius: 10px;
                padding: 12px 28px;     /* ← 14px→12px に縮小 */
                font-size: 14px;
                text-decoration: none;
                width: fit-content;
                transition: opacity 0.15s, transform 0.1s;
                animation: wlUp 0.5s ease 0.24s both;
            }
            .wl-cta:hover { opacity: 0.85; color: #fff; }
            .wl-cta:active { transform: scale(0.97); }
            .wl-cta i { font-size: 16px; }

            /* 右パネル：縦方向にスクロールさせず内側で収める */
            .wl-hero-right {
                width: 340px;           /* ← 380px→340px に縮小 */
                flex-shrink: 0;
                background: #F1EFE8;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                padding: 32px 32px;     /* ← 48px→32px に縮小 */
                gap: 8px;               /* ← 10px→8px に縮小 */
                overflow-y: auto;       /* ← 万が一あふれた場合のフォールバック */
                animation: wlUp 0.5s ease 0.1s both;
            }
            .wl-card {
                background: #fff;
                border-radius: 12px;
                border: 0.5px solid #D3D1C7;
                padding: 12px 18px;     /* ← 14px→12px に縮小 */
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .wl-card-label { font-size: 12px; color: #000000; }
            .wl-card-val { font-size: 20px; font-weight: 600; color: #1C1C1A; } /* ← 22px→20px */
            .wl-card-val span { font-size: 12px; font-weight: 400; color: #B4B2A9; margin-left: 3px; }
            .wl-card.highlight { border-color: var(--theme-main, #E8800A); background: #FFFFFF; }
            .wl-card.highlight .wl-card-val { color: var(--theme-main, #E8800A); }
            .wl-card.highlight-red { border-color: #F5A0A0; background: #FFF5F5; }

            @keyframes wlUp {
                from { opacity: 0; transform: translateY(12px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            /* ===== スマホ レイアウト ===== */
            .wl-sp-page {
                background: #F5F3EF;
                min-height: 100vh;
                flex-direction: column;
                align-items: center;
                padding: 28px 20px 36px;
                gap: 20px;
            }
            .wl-sp-logo-wrap { display: flex; align-items: center; gap: 9px; }
            .wl-sp-logo-mark {
                width: 32px;
                height: 32px;
                background: #1C1C1A;
                border-radius: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .wl-sp-logo-mark i { font-size: 16px; color: #fff; }
            .wl-sp-logo-text { font-size: 24px; font-weight: 600; color: #1C1C1A; }
            .wl-sp-catch { text-align: center; }
            .wl-sp-catch h1 {
                font-size: 24px;
                font-weight: 700;
                color: #1C1C1A;
                line-height: 1.35;
                letter-spacing: -0.02em;
                margin-bottom: 8px;
            }
            .wl-sp-catch h1 em { font-style: normal; color: var(--theme-main, #E8800A); }
            .wl-sp-catch p { font-size: 12px; color: #5F5E5A; line-height: 1.7; }
            .wl-sp-phone-wrap { width: 100%; display: flex; justify-content: center; }
            .wl-sp-phone { width: 200px; background: #2C2C2A; border-radius: 36px; padding: 8px; }
            .wl-sp-phone-notch { width: 56px; height: 9px; background: #2C2C2A; border-radius: 99px; margin: 0 auto 6px; }
            .wl-sp-phone-screen { background: #F0EEE9; border-radius: 28px; overflow: hidden; }
            .wl-sp-topbar {
                background: #fff;
                border-bottom: 3px solid var(--theme-main, #E8800A);
                padding: 8px 12px;
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
            .wl-sp-topbar-title { font-size: 11px; font-weight: 600; color: #2C2C2A; }
            .wl-sp-topbar-accent {
                width: 3px;
                height: 14px;
                background: var(--theme-main, #E8800A);
                border-radius: 2px;
                margin-right: 5px;
                display: inline-block;
                vertical-align: middle;
            }
            .wl-sp-topbar-icon {
                width: 22px;
                height: 22px;
                border-radius: 50%;
                background: #F0EEE9;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 12px;
                color: #5F5E5A;
            }
            .wl-sp-username {
                background: #fff;
                text-align: center;
                padding: 8px 12px 7px;
                border-bottom: 1px solid #E8E6E1;
                font-size: 13px;
                font-weight: 600;
                color: #2C2C2A;
            }
            .wl-sp-username span { font-size: 10px; font-weight: 400; color: #B4B2A9; margin-left: 4px; }
            .wl-sp-list { padding: 8px 8px 10px; display: flex; flex-direction: column; gap: 5px; }
            .wl-sp-item {
                background: #fff;
                border-radius: 10px;
                padding: 8px 12px;
                border: 1px solid #E0DCD5;
                text-align: center;
            }
            .wl-sp-item.highlight { background: #FFF5F5; border-color: #F5A0A0; }
            .wl-sp-item-label { font-size: 9px; color: #888780; margin-bottom: 3px; }
            .wl-sp-item-val { font-size: 20px; font-weight: 700; color: #2C2C2A; line-height: 1.1; }
            .wl-sp-item-val .unit { font-size: 10px; font-weight: 400; color: #B4B2A9; margin-left: 2px; }
            .wl-sp-item-val.date-val { font-size: 13px; font-weight: 600; }
            .wl-sp-phone-home { width: 40px; height: 4px; background: #fff; border-radius: 99px; margin: 6px auto 0; opacity: 0.2; }
            .wl-sp-login {
                width: 100%;
                background: var(--theme-main, #E8800A);
                color: #fff;
                border: none;
                border-radius: 12px;
                padding: 18px;
                font-size: 15px;
                text-align: center;
                text-decoration: none;
                display: block;
                transition: opacity 0.15s;
            }
            .wl-sp-login:hover { opacity: 0.85; color: #fff; }
        </style>
    </head>
    <body>
        <x-alert/>

        {{-- ===== PC表示（md以上で flex、未満で hidden） ===== --}}
        <div class="wl-page hidden md:flex">

            <nav class="wl-nav">
                <div class="wl-nav-logo">
                    <div class="wl-nav-logo-mark">
                        <i class="las la-calendar-check"></i>
                    </div>
                    <span class="wl-nav-logo-text">{{ config('app.name', '有休管理システム') }}</span>
                </div>
                @guest
                    <a href="{{ route('login') }}" class="wl-nav-login">ログイン</a>
                @endguest
            </nav>

            <section class="wl-hero">
                <div class="wl-hero-left">
                    <h1 class="wl-h1">
                        有休を<br>
                        <em>スマートに</em>管理
                    </h1>
                    <p class="wl-sub">
                        有休に関わる情報をひとつの画面で確認
                    </p>
                    @guest
                        <a href="{{ route('login') }}" class="wl-cta">
                            ログインして確認する
                            <i class="las la-arrow-right"></i>
                        </a>
                    @else
                        <a href="{{ route('dashboard') }}" class="wl-cta">
                            ダッシュボードへ
                            <i class="las la-arrow-right"></i>
                        </a>
                    @endguest
                </div>

                <div class="wl-hero-right">
                    <div class="wl-card">
                        <span class="wl-card-label">保有日数</span>
                        <span class="wl-card-val">10.0<span>日</span></span>
                    </div>
                    <div class="wl-card">
                        <span class="wl-card-label">取得日数</span>
                        <span class="wl-card-val">1.0<span>日</span></span>
                    </div>
                    <div class="wl-card highlight-red">
                        <span class="wl-card-label">残日数</span>
                        <span class="wl-card-val">9.0<span>日</span></span>
                    </div>
                    <div class="wl-card">
                        <span class="wl-card-label">義務の日数</span>
                        <span class="wl-card-val">5.0<span>日</span></span>
                    </div>
                    <div class="wl-card highlight-red">
                        <span class="wl-card-label">義務の残日数</span>
                        <span class="wl-card-val">4.0<span>日</span></span>
                    </div>
                    <div class="wl-card">
                        <span class="wl-card-label">義務の期限</span>
                        <span class="wl-card-val" style="font-size:14px;">2027年03月31日</span>
                    </div>
                </div>
            </section>

        </div>{{-- /PC --}}


        {{-- ===== スマホ表示（md未満で flex、以上で hidden） ===== --}}
        <div class="wl-sp-page flex md:hidden">

            <div class="wl-sp-logo-wrap">
                <div class="wl-sp-logo-mark"><i class="las la-calendar-check"></i></div>
                <span class="wl-sp-logo-text">{{ config('app.name', '有休管理システム') }}</span>
            </div>

            <div class="wl-sp-catch">
                <h1>有休を<br><em>スマートに</em>管理</h1>
                <p>有休に関わる情報をひとつの画面で確認</p>
            </div>

            <div class="wl-sp-phone-wrap">
                <div class="wl-sp-phone">
                    <div class="wl-sp-phone-notch"></div>
                    <div class="wl-sp-phone-screen">
                        <div class="wl-sp-topbar">
                            <div style="display:flex;align-items:center;">
                                <span class="wl-sp-topbar-accent"></span>
                                <span class="wl-sp-topbar-title">ダッシュボード</span>
                            </div>
                            <div class="wl-sp-topbar-icon"><i class="las la-user"></i></div>
                        </div>
                        <div class="wl-sp-username">
                            ○○　○○<span>さん</span>
                        </div>
                        <div class="wl-sp-list">
                            <div class="wl-sp-item">
                                <div class="wl-sp-item-label">保有日数</div>
                                <div class="wl-sp-item-val">10.0<span class="unit">日</span></div>
                            </div>
                            <div class="wl-sp-item">
                                <div class="wl-sp-item-label">取得日数</div>
                                <div class="wl-sp-item-val">1.0<span class="unit">日</span></div>
                            </div>
                            <div class="wl-sp-item highlight">
                                <div class="wl-sp-item-label">残日数</div>
                                <div class="wl-sp-item-val">9.0<span class="unit">日</span></div>
                            </div>
                            <div class="wl-sp-item">
                                <div class="wl-sp-item-label">義務の日数</div>
                                <div class="wl-sp-item-val">5.0<span class="unit">日</span></div>
                            </div>
                            <div class="wl-sp-item highlight">
                                <div class="wl-sp-item-label">義務の残日数</div>
                                <div class="wl-sp-item-val">4.0<span class="unit">日</span></div>
                            </div>
                            <div class="wl-sp-item">
                                <div class="wl-sp-item-label">義務の期限</div>
                                <div class="wl-sp-item-val date-val">2027年03月31日（水）</div>
                            </div>
                        </div>
                    </div>
                    <div class="wl-sp-phone-home"></div>
                </div>
            </div>

            @guest
                <a href="{{ route('login') }}" class="wl-sp-login">ログインして始める</a>
            @else
                <a href="{{ route('dashboard') }}" class="wl-sp-login">ダッシュボードへ</a>
            @endguest

        </div>{{-- /スマホ --}}

    </body>
</html>