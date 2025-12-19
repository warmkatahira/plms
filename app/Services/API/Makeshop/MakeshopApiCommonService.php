<?php

namespace App\Services\API\Makeshop;

// 列挙
use App\Enums\API\MakeshopEnum;
use App\Enums\SystemEnum;
// その他
use Illuminate\Support\Facades\Http;
use Carbon\CarbonImmutable;

class MakeshopApiCommonService
{
    // MakeshopAPIにリクエストを送信する共通部分
    public function request(array $body)
    {
        // 現在のUNIXタイムスタンプを取得
        $timestamp = time();
        // 必要なヘッダー情報を設定してAPIへPOSTリクエストを送信
        return Http::withHeaders([
            'Authorization'     => MakeshopEnum::ACCESS_TOKEN,
            'Content-Type'      => 'application/json',
            'X-API-Key'         => MakeshopEnum::API_KEY,
            'X-Timestamp'       => $timestamp,
        ])->post(MakeshopEnum::ENDPOINT, $body);
    }

    // レスポンスエラーをチェック
    public function checkResponseError($response_array, $root_key, $result_key, $errors)
    {
        // GraphQLレベルのエラーが発生している場合
        if(isset($response_array['errors'][0]['message'])){
            // エラーを返す
            return [
                'message' => $response_array['errors'][0]['message'],
            ];
        }
        // 結果の分だけループ処理
        foreach($response_array['data'][$root_key][$result_key] as $result){
            // メッセージがnull以外の場合
            if(!is_null($result[MakeshopEnum::RESPONSE_COLUMN_MESSAGE_MAKESHOP])){
                // 1件分のエラー情報を格納する配列を初期化
                $error = [];
                // 定義済みの「Makeshop → smooth」カラムマッピングの分だけループ処理
                foreach(MakeshopEnum::RESPONSE_COLUMN_MAPPING_MAKESHOP_AND_SMOOTH as $makeshop_key => $smooth_key){
                    // 現在のMakeshopキーがレスポンス結果に存在する場合
                    if(array_key_exists($makeshop_key, $result)){
                        // smooth側のキー名で配列に格納
                        $error[$smooth_key] = $result[$makeshop_key];
                    }
                }
                // エラーが1件でもある場合
                if(!empty($error)){
                    // 配列にエラーを追加
                    $errors[] = $error;
                }
            }
        }
        return $errors;
    }

    // レスポンスエラーをファイルに出力
    public function exportResponseError($errors, $title)
    {
        // エラーが無い場合
        if(empty($errors)){
            // 処理をスキップ
            return;
        }
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // チャンクサイズを設定
        $chunk_size = 500;
        // チャンクサイズ毎に分割
        $chunks = array_chunk($errors, $chunk_size);
        // ファイル名を指定
        $file_name = '【'.SystemEnum::getSystemTitle().'】'.$title.'_'.$nowDate->isoFormat('Y年MM月DD日HH時mm分ss秒').'.csv';
        // 保存場所を設定
        $csv_file_path = storage_path('app/public/export/error/'.$file_name);
        // キーを格納する配列を初期化
        $all_keys = [];
        // チャンクの分だけループ処理
        foreach($chunks as $chunk){
            // レコードの分だけループ処理
            foreach($chunk as $item){
                // 各レコードのカラム名を配列に追加
                $all_keys = array_merge($all_keys, array_keys($item));
            }
        }
        // 重複を除外して、キーを一意にする
        $all_keys = array_unique($all_keys);
        // マッピング順に並び替え（定義順を優先し、定義外は後ろに追加）
        $sorted_keys = array_values(array_intersect(array_keys(MakeshopEnum::RESPONSE_COLUMN_MAPPING_SMOOTH_AND_JP), $all_keys));
        $remaining_keys = array_diff($all_keys, $sorted_keys);
        $final_keys = array_merge($sorted_keys, $remaining_keys);
        // キーを日本語に変換
        $header = array_map(function ($key) {
            return MakeshopEnum::RESPONSE_COLUMN_MAPPING_SMOOTH_AND_JP[$key] ?? $key;
        }, $final_keys);
        // ヘッダー行を書き込む
        $csv_content = "\xEF\xBB\xBF" . implode(',', $header) . "\n";
        // チャンク毎のループ処理
        foreach($chunks as $chunk){
            // レコード毎のループ処理
            foreach($chunk as $item){
                // 書き込む値を格納する配列を初期化
                $row = [];
                // キーの分だけループ処理
                foreach($final_keys as $key){
                    // 値を配列に格納（存在しないキーは空文字）
                    $row[] = $item[$key] ?? '';
                }
                // 書き込む
                $csv_content .= implode(',', $row) . "\n";
            }
        }
        // ファイルに出力
        file_put_contents($csv_file_path, $csv_content);
        return $file_name;
    }
}