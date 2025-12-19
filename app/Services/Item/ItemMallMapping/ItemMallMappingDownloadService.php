<?php

namespace App\Services\Item\ItemMallMapping;

// モデル
use App\Models\Item;
use App\Models\ItemMall;
use App\Models\SetItemMall;
use App\Models\Mall;
// サービス
use App\Services\Common\ZipService;
// 列挙
use App\Enums\SystemEnum;
// その他
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Storage;

class ItemMallMappingDownloadService
{
    // ダウンロードするデータを取得
    public function getDownloadDataItem($items)
    {
        // インスタンス化
        $ZipService = new ZipService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // ファイルを出力するディレクトリを作成
        $directory = $this->makeDirectory($nowDate, '単品商品');
        // モールを取得
        $malls = Mall::getAll()->get();
        // モールの分だけループ処理
        foreach($malls as $mall){
            // モール商品マッピングデータを作成
            $this->createItemMallMappingFile($nowDate, $directory['directory_path'], $mall, $items);
        }
        // Zipファイルを作成
        return $ZipService->createZip($directory['directory_name'], $directory['directory_path']);
    }

    // ダウンロードするデータを取得
    public function getDownloadDataSetItem($set_items)
    {
        // インスタンス化
        $ZipService = new ZipService;
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // ファイルを出力するディレクトリを作成
        $directory = $this->makeDirectory($nowDate, 'セット商品');
        // モールを取得
        $malls = Mall::getAll()->get();
        // モールの分だけループ処理
        foreach($malls as $mall){
            // モール商品マッピングデータを作成
            $this->createSetItemMallMappingFile($nowDate, $directory['directory_path'], $mall, $set_items);
        }
        // Zipファイルを作成
        return $ZipService->createZip($directory['directory_name'], $directory['directory_path']);
    }

    // ファイルを出力するディレクトリを作成
    public function makeDirectory($nowDate, $item_type)
    {
        // 保存先のディレクトリ名を決める
        $directory_name = "【".SystemEnum::getSystemTitle()."】モール×".$item_type."マッピングデータ_" . $nowDate->format('Y年m月d日H時i分s秒');
        // ディレクトリのパスを取得
        $directory_path = 'export/' . $directory_name;
        // 既に存在しているディレクトリではない場合
        if(!Storage::disk('public')->exists($directory_path)){
            // 保存先のディレクトリを作成
            Storage::disk('public')->makeDirectory($directory_path);
        }
        return compact('directory_name', 'directory_path');
    }

    // モール商品マッピングデータを作成(商品)
    public function createItemMallMappingFile($nowDate, $directory_path, $mall, $items)
    {
        // ファイル名を取得
        $file_name = "【".SystemEnum::getSystemTitle()."】【" . $mall->mall_name . "】モール×単品商品マッピングデータ_" . $nowDate->format('Ymd') . ".csv";
        // ファイルパスを取得
        $file_path = $directory_path . '/' . $file_name;
        // 一時ファイルを生成（PHPファイルシステム上）
        $temp_file = tmpfile();
        $meta = stream_get_meta_data($temp_file);
        $temp_file_path = $meta['uri'];
        // ファイルハンドルで書き込み
        $handle = fopen($temp_file_path, 'w');
        // ヘッダー行の書き込み
        $header = ItemMall::downloadHeader();
        fputcsv($handle, array_map(fn($v) => mb_convert_encoding($v, 'SJIS-win', 'UTF-8'), $header));
        // チャンクサイズを指定
        $chunk_size = 1000;
        // レコードをチャンクごとに書き込む
        $items->chunk($chunk_size, function ($chunk) use ($handle, $mall){
            // 商品の分だけループ処理
            foreach($chunk as $item){
                // モール商品コードとモールバリエーションコードを変数に格納
                $mall_item_code = $item->{'mall_item_code_' . $mall->mall_id};
                $mall_variation_code = $item->{'mall_variation_code_' . $mall->mall_id};
                // 変数に情報を格納
                $row = [
                    $item->item_id,
                    $mall->mall_id,
                    $item->item_code,
                    $item->item_jan_code,
                    $item->item_name,
                    $item->item_category_1,
                    $item->item_category_2,
                    $mall_item_code ? "'" . $mall_item_code : '',
                    $mall_variation_code ? "'" . $mall_variation_code : '',
                ];
                // SJIS変換
                $sjisRow = array_map(fn($v) => mb_convert_encoding($v, 'SJIS-win', 'UTF-8'), $row);
                // 書き込む
                fputcsv($handle, $sjisRow);
            };
        });
        // ファイルを閉じる
        fclose($handle);
        // ファイルを保存
        Storage::disk('public')->put($file_path, file_get_contents($temp_file_path));
        // 一時ファイル削除
        fclose($temp_file);
    }

    // モール商品マッピングデータを作成(セット商品)
    public function createSetItemMallMappingFile($nowDate, $directory_path, $mall, $set_items)
    {
        // ファイル名を取得
        $file_name = "【".SystemEnum::getSystemTitle()."】【" . $mall->mall_name . "】モール×セット商品マッピングデータ_" . $nowDate->format('Ymd') . ".csv";
        // ファイルパスを取得
        $file_path = $directory_path . '/' . $file_name;
        // 一時ファイルを生成（PHPファイルシステム上）
        $temp_file = tmpfile();
        $meta = stream_get_meta_data($temp_file);
        $temp_file_path = $meta['uri'];
        // ファイルハンドルで書き込み
        $handle = fopen($temp_file_path, 'w');
        // ヘッダー行の書き込み
        $header = SetItemMall::downloadHeader();
        fputcsv($handle, array_map(fn($v) => mb_convert_encoding($v, 'SJIS-win', 'UTF-8'), $header));
        // チャンクサイズを指定
        $chunk_size = 1000;
        // レコードをチャンクごとに書き込む
        $set_items->chunk($chunk_size, function ($chunk) use ($handle, $mall){
            // 商品の分だけループ処理
            foreach($chunk as $set_item){
                // モールセット商品コードを変数に格納
                $mall_set_item_code = $set_item->{'mall_set_item_code_' . $mall->mall_id};
                // 変数に情報を格納
                $row = [
                    $set_item->set_item_id,
                    $mall->mall_id,
                    $set_item->set_item_code,
                    $set_item->set_item_name,
                    $mall_set_item_code ? "'" . $mall_set_item_code : '',
                ];
                // SJIS変換
                $sjisRow = array_map(fn($v) => mb_convert_encoding($v, 'SJIS-win', 'UTF-8'), $row);
                // 書き込む
                fputcsv($handle, $sjisRow);
            };
        });
        // ファイルを閉じる
        fclose($handle);
        // ファイルを保存
        Storage::disk('public')->put($file_path, file_get_contents($temp_file_path));
        // 一時ファイル削除
        fclose($temp_file);
    }
}