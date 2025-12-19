<?php

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
// その他
use Illuminate\Support\Facades\Schedule;

// +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-   Daily   +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
// 受注データの削除を毎日「02:00」に実行
Schedule::command('order_import_file_delete')->dailyAt('02:00');
// データ取込エラーの削除を毎日「02:00」に実行
Schedule::command('import_error_file_delete')->dailyAt('02:00');
// 商品アップロードデータの削除を毎日「02:00」に実行
Schedule::command('item_upload_file_delete')->dailyAt('02:00');
// 荷札データ発行ディレクトリと履歴レコードの削除を毎日「02:00」に実行
Schedule::command('nifuda_issue_delete')->dailyAt('02:00');
// DBバックアップの削除を毎日「03:00」に実行
Schedule::command('backup_db_delete')->dailyAt('03:00');
// DBバックアップを毎日「03:30」に実行
Schedule::command('backup:run --disable-notifications --only-db --only-to-disk=db_backup_normal')->dailyAt('03:30');
