<x-guest-layout>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; padding: 0; }

        .login-page {
            height: 100vh;
            overflow: hidden;
            background: #FAFAF8;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .login-card {
            width: 100%;
            max-width: 360px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 32px;
        }

        /* ロゴ */
        .login-logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .login-logo-mark {
            width: 36px;
            height: 36px;
            background: #1C1C1A;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-logo-mark i { font-size: 19px; color: #fff; }
        .login-logo-text {
            font-size: 24px;
            font-weight: 700;
            color: #1C1C1A;
            letter-spacing: -0.01em;
        }

        /* フォーム */
        .login-form {
            width: 100%;
            background: #fff;
            border: 0.5px solid #D3D1C7;
            border-radius: 14px;
            padding: 28px 28px 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .login-field { display: flex; flex-direction: column; gap: 5px; }
        .login-label {
            font-size: 12px;
            font-weight: 600;
            color: #5F5E5A;
        }
        .login-input {
            width: 100%;
            background: #FAFAF8;
            border: 0.5px solid #D3D1C7;
            border-radius: 8px;
            padding: 10px 13px;
            font-size: 14px;
            color: #1C1C1A;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        .login-input:focus {
            border-color: var(--theme-main, #E8800A);
            box-shadow: 0 0 0 3px rgba(232,128,10,0.1);
        }
        .login-submit {
            width: 100%;
            background: #1C1C1A;
            color: #fff;
            border: none;
            border-radius: 9px;
            padding: 12px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 4px;
            transition: opacity 0.15s, transform 0.1s;
        }
        .login-submit:hover { opacity: 0.75; }
        .login-submit:active { transform: scale(0.98); }

        /* エラー */
        .login-errors {
            background: #FFF5F5;
            border: 0.5px solid #F5A0A0;
            border-radius: 8px;
            padding: 10px 14px;
        }
        .login-errors p {
            font-size: 12px;
            color: #A32D2D;
            margin: 0;
            line-height: 1.7;
        }
    </style>

    <div class="login-page">
        <div class="login-card">

            {{-- ロゴ --}}
            <div class="login-logo">
                <div class="login-logo-mark">
                    <i class="las la-calendar-check"></i>
                </div>
                <span class="login-logo-text">{{ config('app.name', '有休管理システム') }}</span>
            </div>

            {{-- フォーム --}}
            <div class="login-form">

                {{-- バリデーションエラー --}}
                @if ($errors->any())
                    <div class="login-errors">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <x-validation-error-msg />

                <form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:16px;">
                    @csrf

                    {{-- ユーザーID --}}
                    <div class="login-field">
                        <label class="login-label" for="user_id">ユーザーID</label>
                        @if(config('app.env') === 'local')
                            <x-auth.input id="user_id" label="" type="text" db="katahira" />
                        @else
                            <x-auth.input id="user_id" label="" type="text" :db="null" />
                        @endif
                    </div>

                    {{-- パスワード --}}
                    <div class="login-field">
                        <label class="login-label" for="password">パスワード</label>
                        @if(config('app.env') === 'local')
                            <x-auth.input id="password" label="" type="password" db="katahira134" />
                        @else
                            <x-auth.input id="password" label="" type="password" :db="null" />
                        @endif
                    </div>

                    <button type="submit" class="login-submit">ログイン</button>
                </form>
            </div>

        </div>
    </div>
</x-guest-layout>