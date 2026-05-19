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
    </head>
    <body>
        <x-alert/>
        {{-- PC：ロゴ中央・ボタン右上 --}}
        @guest
            <div class="hidden md:block absolute top-8 right-8">
                <a href="{{ route('login') }}" class="btn rounded-md bg-theme-main text-center py-5 px-10 text-sm">ログイン</a>
            </div>
        @endguest
        <div class="hidden md:flex items-center justify-center min-h-screen pb-96">
            <img src="{{ asset('image/plms_logo.svg') }}" class="welcome_logo">
        </div>
        {{-- スマホ：ロゴ上・ボタン下 --}}
        <div class="flex md:hidden flex-col items-center min-h-screen px-8 pt-5 gap-8">
            <img src="{{ asset('image/plms_logo.svg') }}" class="welcome_logo">
            @guest
                <a href="{{ route('login') }}" class="btn rounded-md bg-theme-main text-center w-full py-8 text-sm">ログイン</a>
            @endguest
        </div>
    </body>
</html>