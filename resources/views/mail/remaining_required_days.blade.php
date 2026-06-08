<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- favicon -->
        <link rel="shortcut icon" href="{{ asset('image/favicon.svg') }}">

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/sass/theme.scss'])

        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Alkatra&family=Kosugi+Maru&family=Lemonada&family=Rowdies:wght@300&display=swap" rel="stylesheet">
    </head>
    <body style="font-family: 'Kosugi Maru';">
        <div style="font-size: 12px;">
            <div>
                ※返信不可※<br>
                ※このメールは{{ config('app.name', 'Laravel') }}から自動配信されています。
            </div>
            <br>
            <div>
                以下の従業員に義務残日数があります。<br>
                有休管理システムにログインし、義務日数と期限を確認してください。<br>
                本メールが届いた営業所管理者は、義務残が0になるよう促進してください。
            </div>
            @foreach($employee_list as $employee)
                <p>・{{ $employee['user_name'] }}　【残{{ $employee['remaining_required_days'] }}日】</p>
            @endforeach
            <div>
                ログインURL：{{ config('app.url') }}<br>
                有休休暇の取得管理について：
            </div>
        </div>
    </body>
</html>
