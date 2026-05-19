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
            <p>※このメールは{{ config('app.name', 'Laravel') }}から自動配信されています。</p>
            <p>以下の従業員に義務残日数があります。</p>
            @foreach($employee_list as $employee)
                <p>・{{ $employee['user_name'] }}　残{{ $employee['remaining_required_days'] }}日</p>
            @endforeach
            <div>
                <p>ログインURL：{{ config('app.url') }}</p>
            </div>
        </div>
    </body>
</html>
