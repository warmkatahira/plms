<?php

namespace App\Services\Shipping\Nifuda;

// モデル
use App\Models\Order;
use App\Models\ShippingGroup;
use App\Models\ShippingMethod;
use App\Models\BaseShippingMethod;
use App\Models\YamatoSorting;
use App\Models\NifudaCreateHistory;
// 列挙
use App\Enums\OrderStatusEnum;
use App\Enums\DeliveryCompanyEnum;
use App\Enums\DeliveryTimeZoneEnum;
use App\Enums\SagawaSealCodeEnum;
use App\Enums\SystemEnum;
use App\Enums\ShippingMethodEnum;
use App\Enums\NifudaEnum;
use App\Enums\SagawaNifudaHeaderEnum;
// その他
use Carbon\CarbonImmutable;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NifudaCreateService
{
    // 作成対象を取得
    public function getCreateOrderByShippingMethod($shipping_method_id)
    {
        // 指定された出荷グループ×配送方法の受注を取得
        $orders = Order::with('order_category.shipper')
                    ->with('order_items')
                    ->where('shipping_group_id', session('search_shipping_group_id'))
                    ->where('shipping_method_id', $shipping_method_id)
                    ->select('orders.*')
                    ->orderBy('order_control_id');
        // 作成できる荷札データがない場合
        if(!$orders->exists()){
            throw new \RuntimeException('作成できる荷札データがありません。');
        }
        return $orders;
    }

    // 荷札データを作成
    public function createNifuda($shipping_method_id, $orders, $shipping_group_id)
    {
        // 現在の日時を取得
        $nowDate = CarbonImmutable::now();
        // 出荷グループを取得
        $shipping_group = ShippingGroup::getSpecify($shipping_group_id)->first();
        // 配送方法を取得
        $shipping_method = ShippingMethod::getSpecify($shipping_method_id)->first();
        // 倉庫別配送方法を取得
        $base_shipping_method = BaseShippingMethod::getSpecifyByBaseIdAndShippingMethodId($shipping_group->shipping_base_id, $shipping_method_id)->first();
        // 保存先のディレクトリ名を決める
        $directory_name = $shipping_group->shipping_group_name.'_'.$shipping_method->delivery_company_and_shipping_method.'_'.$nowDate->format('Y-m-d_H-i-s');
        // 既に存在しているディレクトリではない場合
        if(!Storage::disk('public')->exists('nifuda/'.$directory_name)){
            // 保存先のディレクトリを作成
            Storage::disk('public')->makeDirectory('nifuda/'.$directory_name);
        }
        // 運送会社によって処理を可変
        // 佐川急便
        if($shipping_method->delivery_company_id === DeliveryCompanyEnum::SAGAWA_ID){
            $download_filename = '【'.$shipping_method->delivery_company_and_shipping_method.'】荷札データ_'.$nowDate->isoFormat('Y年MM月DD日HH時mm分ss秒').'.csv';
            $this->createSagawaForJp($base_shipping_method, $orders, $shipping_group, $download_filename, $directory_name);
        }
        // ヤマト運輸
        if($shipping_method->delivery_company_id === DeliveryCompanyEnum::YAMATO_ID){
            $download_filename = '【'.$shipping_method->delivery_company_and_shipping_method.'】荷札データ_'.$nowDate->isoFormat('Y年MM月DD日HH時mm分ss秒').'.xlsx';
            $this->createYamato($base_shipping_method, $orders, $shipping_group, $download_filename, $directory_name);
        }
        return $directory_name;
    }

    // 佐川急便(CSV)
    public function createSagawaForJp($base_shipping_method, $orders, $shipping_group, $download_filename, $directory_name)
    {
        // 作成ファイル数をカウントする変数を初期化
        $make_file_count = 0;
        // チャンクサイズを指定
        $chunkSize = 1000;
        // レコードをチャンクごとに書き込む
        $orders->chunk($chunkSize, function ($orders) use ($base_shipping_method, $shipping_group, $download_filename, $directory_name, &$make_file_count) {
            // 作成ファイル数をカウントアップ
            $make_file_count++;
            // ダウンロードする情報を格納する配列を初期化
            $download_data = [];
            // 受注の分だけループ処理
            foreach($orders as $order){
                // 配送先住所と荷送人住所からスペースを取り除く
                $ship_address = str_replace(array(" ", "　"), "", $order->ship_address);
                $shipper_address = str_replace(array(" ", "　"), "", $order->order_category->shipper->shipper_address);
                // 1件分の情報を格納
                $param = [
                    // 各情報を出力
                    '','',
                    $order->ship_tel,   // 配送先電話番号
                    $order->ship_postal_code,  // 配送先郵便番号
                    $ship_address,    // 配送先住所
                    '','',
                    $order->ship_name,  // 配送先名
                    '',
                    $order->order_control_id,   // 受注管理ID
                    $base_shipping_method->setting_1, // お客様コード
                    '','','','','',
                    $base_shipping_method->setting_1, // ご依頼主コード
                    $order->order_category->shipper->shipper_tel, // ご依頼主電話番号
                    $order->order_category->shipper->shipper_postal_code, // ご依頼主郵便番号
                    $shipper_address, // ご依頼主住所
                    '',
                    $order->order_category->shipper->shipper_name, // ご依頼主名
                    '','',
                    NifudaEnum::create_product_name($order->order_category->nifuda_product_name_1, $order), // 品名1
                    NifudaEnum::create_product_name($order->order_category->nifuda_product_name_2, $order), // 品名2
                    NifudaEnum::create_product_name($order->order_category->nifuda_product_name_3, $order), // 品名3
                    NifudaEnum::create_product_name($order->order_category->nifuda_product_name_4, $order), // 品名4
                    NifudaEnum::create_product_name($order->order_category->nifuda_product_name_5, $order), // 品名5
                    '','','','','','','','','','','','','','','',
                    is_null($order->desired_delivery_date) ? '' : CarbonImmutable::parse($order->desired_delivery_date)->format('Y/m/d'), // 配送希望日
                    DeliveryTimeZoneEnum::sagawa_time_zone_get($order->desired_delivery_time), // 配送希望時間
                    '','','','','',
                    '011',  // 指定シール1(取注)
                    SagawaSealCodeEnum::sagawa_seal_code_get($base_shipping_method->e_hiden_version, $order->desired_delivery_date, $order->desired_delivery_time),     // 指定シール2(日時指定)
                    '',     // 指定シール3
                    '','','','','','',
                    CarbonImmutable::parse($shipping_group->estimated_shipping_date)->format('Y/m/d'),  // 出荷日
                ];
                // ダウンロードする情報を格納する配列に格納
                $download_data[] = $param;
            }
            // 配列をワークシートに書き出し
            $spreadsheet = new Spreadsheet();
            $worksheet = $spreadsheet->getActiveSheet();
            // ヘッダーを書き出し
            foreach (SagawaNifudaHeaderEnum::header as $col => $cellData) {
                $worksheet->setCellValue([$col + 1, 1], $cellData);
            }
            // 内容を書き出し
            foreach ($download_data as $row => $rowData) {
                foreach ($rowData as $col => $cellData) {
                    $worksheet->setCellValue([$col + 1, $row + 2], $cellData);
                }
            }
            // ファイルの保存先パスを取得
            $file_path = Storage::disk('public')->path('nifuda/'.$directory_name.'/【'.sprintf('%02d', $make_file_count).'】'.$download_filename);
            // CSVファイルを保存する
            $writer = new Csv($spreadsheet);
            $writer->setSheetIndex(0);
            $writer->setDelimiter(',');
            $writer->setEnclosure('"');
            $writer->setLineEnding("\r\n");
            $writer->setUseBOM(false);
            $writer->setOutputEncoding('SJIS');
            $writer->save($file_path);
        });
    }

    // ヤマト運輸
    public function createYamato($base_shipping_method, $orders, $shipping_group, $download_filename, $directory_name)
    {
        // 作成ファイル数をカウントする変数を初期化
        $make_file_count = 0;
        // チャンクサイズを指定
        $chunk_size = 1000;
        // レコードをチャンクごとに書き込む
        $orders->chunk($chunk_size, function ($orders) use ($base_shipping_method, $shipping_group, $download_filename, $directory_name, &$make_file_count) {
            // 作成ファイル数をカウントアップ
            $make_file_count++;
            // テンプレートを読み込む
            $templatePath = public_path('template/yamato.xlsx');
            $spreadsheet = IOFactory::load($templatePath);
            $worksheet = $spreadsheet->getActiveSheet();
            // データを書き込む位置を初期化
            $row = 2;
            // 受注の分だけループ処理
            foreach($orders as $order){
                // 配送先住所と荷送人住所からスペースを取り除く
                $ship_address = str_replace(array(" ", "　"), "", $order->ship_address);
                $shipper_address = str_replace(array(" ", "　"), "", $order->order_category->shipper->shipper_address);
                // 各情報を出力
                $worksheet->setCellValue('A'.$row, $order->order_control_id);   // 受注管理ID
                $worksheet->setCellValue('B'.$row, $base_shipping_method->setting_3);  // 送り状種類
                $worksheet->setCellValue('E'.$row, CarbonImmutable::parse($shipping_group->estimated_shipping_date)->format('Y/m/d'));  // 出荷予定日
                $worksheet->setCellValue('F'.$row, is_null($order->desired_delivery_date) ? '' : CarbonImmutable::parse($order->desired_delivery_date)->format('Y/m/d')); // 配送希望日
                $worksheet->setCellValue('G'.$row, DeliveryTimeZoneEnum::yamato_time_zone_get($order->desired_delivery_time));   // 配送希望時間
                $worksheet->setCellValue('I'.$row, $order->ship_tel);   // 配送先電話番号
                $worksheet->setCellValue('K'.$row, $order->ship_postal_code);  // 配送先郵便番号
                $worksheet->setCellValue('L'.$row, mb_substr($ship_address, 0, 21));    // 配送先住所1
                $worksheet->setCellValue('M'.$row, mb_substr($ship_address, 21, null));    // 配送先住所2
                $worksheet->setCellValue('P'.$row, $order->ship_name);  // 配送先名
                $worksheet->setCellValue('T'.$row, $order->order_category->shipper->shipper_tel); // 荷送人電話番号
                $worksheet->setCellValue('V'.$row, $order->order_category->shipper->shipper_postal_code); // 荷送人郵便番号
                $worksheet->setCellValue('W'.$row, mb_substr($shipper_address, 0, 16)); // 荷送人住所1
                $worksheet->setCellValue('X'.$row, mb_substr($shipper_address, 16, null));    // 配送先住所2
                $worksheet->setCellValue('Y'.$row, $order->order_category->shipper->shipper_name); // 荷送人名
                $worksheet->setCellValue('AB'.$row, NifudaEnum::create_product_name($order->order_category->nifuda_product_name_1, $order)); // 品名1
                $worksheet->setCellValue('AD'.$row, NifudaEnum::create_product_name($order->order_category->nifuda_product_name_2, $order)); // 品名2
                $worksheet->setCellValue('AG'.$row, NifudaEnum::create_product_name($order->order_category->nifuda_product_name_3, $order)); // 品名3(記事)
                $worksheet->setCellValue('AN'.$row, $base_shipping_method->setting_1); // 請求先顧客コード
                $worksheet->setCellValue('AP'.$row, $base_shipping_method->setting_2); // 運賃管理番号
                $worksheet->setCellValue('BW'.$row, '荷主名'); // 検索キータイトル1
                $worksheet->setCellValue('BX'.$row, SystemEnum::CUSTOMER_NAME_EN); // 検索キー1
                $worksheet->setCellValue('BY'.$row, '出荷グループID'); // 検索キータイトル2
                $worksheet->setCellValue('BZ'.$row, sprintf('%02d', $order->shipping_group_id)); // 検索キー2
                $worksheet->setCellValue('CA'.$row, '出荷グループID連番'); // 検索キータイトル3
                $worksheet->setCellValue('CB'.$row, sprintf('%02d', $make_file_count)); // 検索キー3
                // データを書き込む位置をカウントアップ
                $row++;
            }
            // ファイルの保存先パスを取得
            $file_path = Storage::disk('public')->path('nifuda/'.$directory_name.'/【'.sprintf('%02d', $make_file_count).'】'.$download_filename);
            // Excelファイルを保存する
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save($file_path);
        });
    }

    // 荷札データ作成履歴を追加
    public function createNifudaCreateHistory($shipping_method_id, $directory_name, $shipping_group_id, $created_count)
    {
        NifudaCreateHistory::create([
            'shipping_group_id'     => $shipping_group_id,
            'shipping_method_id'    => $shipping_method_id,
            'created_count'         => $created_count,
            'directory_name'        => $directory_name,
            'created_by'            => Auth::user()->user_no,
        ]);
    }

    // 作成に必要な情報を取得
    public function getCreateInfo($chk)
    {
        // 選択されている注文の配送方法IDと出荷グループIDの重複を取り除く
        return Order::whereIn('order_control_id', $chk)
                    ->select('shipping_method_id', 'shipping_group_id')
                    ->distinct()
                    ->first();
    }

    // 作成対象を取得
    public function getCreateOrderBySelectOrder($chk)
    {
        // 指定された出荷グループ×配送方法の受注を取得
        $orders = Order::with('order_category.shipper')
                    ->with('order_items')
                    ->whereIn('order_control_id', $chk)
                    ->select('orders.*')
                    ->orderBy('order_control_id');
        // 作成できる荷札データがない場合
        if(!$orders->exists()){
            throw new \RuntimeException('作成できる荷札データがありません。');
        }
        return $orders;
    }
}