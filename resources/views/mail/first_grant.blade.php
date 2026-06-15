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
                以下の従業員に初回有休が付与されました。<br>
                有休管理システムにログインし、有休日数を確認してください。<br>
                義務日数がある方は必ず期限までに消化いただくようお願いします。<br>
                なお、本メールが届いた営業所管理者は、対象従業員に<br>
                有休取得方法についてご案内をお願いします。<br>
            </div>
            @foreach($employee_names as $name)
                <p>・{{ $name }}</p>
            @endforeach
            <div>
                ログインURL：{{ config('app.url') }}<br>
                有休休暇の取得管理について：https://warm-inc.cybozu.com/o/ag.cgi?page=FileView&ffid=87405<br>
                勤怠申請マニュアル　　　　：https://warm-inc.cybozu.com/o/ag.cgi?page=FileView&ffid=87361
            </div>
        </div>
    </body>
</html>
