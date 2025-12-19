<?php

namespace App\Http\Controllers\Item\ItemMallMapping;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// サービス
use App\Services\Item\Item\ItemSearchService;
use App\Services\Item\SetItem\SetItemSearchService;
use App\Services\Item\ItemMallMapping\ItemMallMappingSearchService;
use App\Services\Item\ItemMallMapping\ItemMallMappingDownloadService;

class ItemMallMappingDownloadController extends Controller
{
    public function download_item()
    {
        // インスタンス化
        $ItemSearchService = new ItemSearchService;
        $ItemMallMappingSearchService = new ItemMallMappingSearchService;
        $ItemMallMappingDownloadService = new ItemMallMappingDownloadService;
        // 検索に使用するクエリを取得
        $query = $ItemMallMappingSearchService->getSearchQueryItem();
        // 検索結果を取得
        $result = $ItemSearchService->getSearchResult($query);
        // ダウンロードするデータを取得
        $zip_file_path = $ItemMallMappingDownloadService->getDownloadDataItem($result);
        // 作成したZipファイルをダウンロード(ダウンロード後に削除している)
        return response()->download($zip_file_path)->deleteFileAfterSend(true);
    }

    public function download_set_item()
    {
        // インスタンス化
        $SetItemSearchService = new SetItemSearchService;
        $ItemMallMappingSearchService = new ItemMallMappingSearchService;
        $ItemMallMappingDownloadService = new ItemMallMappingDownloadService;
        // 検索に使用するクエリを取得
        $query = $ItemMallMappingSearchService->getSearchQuerySetItem();
        // 検索結果を取得
        $result = $SetItemSearchService->getSearchResult($query);
        // ダウンロードするデータを取得
        $zip_file_path = $ItemMallMappingDownloadService->getDownloadDataSetItem($result);
        // 作成したZipファイルをダウンロード(ダウンロード後に削除している)
        return response()->download($zip_file_path)->deleteFileAfterSend(true);
    }
}
