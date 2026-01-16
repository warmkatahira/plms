<?php

namespace App\Services\Common;

// モデル
use App\Models\ImportHistory;

class ImportHistoryCreateService
{
    // import_historiesテーブルへ追加
    public function createImportHistory($import_original_file_name, $import_type, $error_file_name, $message)
    {
        ImportHistory::create([
            'import_file_name'  => $import_original_file_name,
            'import_type'       => $import_type,
            'error_file_name'   => $error_file_name,
            'message'           => $message,
        ]);
    }
}