<x-guest-layout>
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        .login-page {
            height: 100vh;
            width: 100vw;
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
        .login-form-title {
            font-size: 15px;
            font-weight: 700;
            color: #1C1C1A;
            margin-bottom: 4px;
        }
        .login-field { display: flex; flex-direction: column; gap: 5px; }
        .login-label {
            font-size: 12px;
            font-weight: 600;
            color: #5F5E5A;
        }
        .login-hint {
            font-size: 11px;
            color: #B4B2A9;
            margin: 2px 0 0 2px;
            line-height: 1.6;
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
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
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

                <p class="login-form-title">パスワード変更</p>

                {{-- バリデーションエラー --}}
                @if ($errors->any())
                    <div class="login-errors">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <x-validation-error-msg />

                <form method="POST" action="{{ route('password.change.update') }}" style="display:flex;flex-direction:column;gap:16px;">
                    @csrf

                    {{-- 新しいパスワード --}}
                    <div class="login-field">
                        <label class="login-label" for="password">新しいパスワード</label>
                        <x-auth.input id="password" label="" type="password" :db="null" />
                        <p class="login-hint">・8〜20文字以内で設定してください<br>・英数字が使用できます</p>
                    </div>

                    {{-- 確認用パスワード --}}
                    <div class="login-field">
                        <label class="login-label" for="password_confirmation">新しいパスワード（確認用）</label>
                        <x-auth.input id="password_confirmation" label="" type="password" :db="null" />
                    </div>

                    <button type="submit" class="login-submit">
                        <i class="las la-check"></i>
                        変更する
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-guest-layout>