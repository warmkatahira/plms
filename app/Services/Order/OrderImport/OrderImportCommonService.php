<?php

namespace App\Services\Order\OrderImport;

// モデル
use App\Models\OrderImport;
use App\Models\OrderImportHistory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Item;
// サービス
use App\Services\Common\ImportErrorCreateService;
use App\Services\Order\OrderImport\AutoProcessApplyService;
use App\Services\Order\OrderAllocate\AllocateService;
use App\Services\Order\OrderAllocate\ItemAllocate\ItemAllocateService;
// 列挙
use App\Enums\ShippingMethodEnum;
// 例外
use App\Exceptions\OrderImportException;
// その他
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderImportCommonService
{
    // order_importsテーブルへ追加から自動処理適用までの共通処理
    public function processOrderImportCommon($order_create_data, $error_file_name, $message, $nowDate, $import_type)
    {
        // インスタンス化
        $ImportErrorCreateService   = new ImportErrorCreateService;
        $AutoProcessApplyService    = new AutoProcessApplyService;
        $AllocateService = new AllocateService;
        $ItemAllocateService = new ItemAllocateService;
        // order_importsへデータを追加
        $this->createArrayImportData($order_create_data);
        // 既に取込済みの受注を削除して、削除後の注文番号数を返す
        $order_num = $this->deleteImportedOrder();
        // インポートできない受注があればエラーファイルを作成
        if(!empty($order_num['import_already'])){
            // メッセージを格納
            $message = '既に取り込み済みの注文がありました。';
            // インポートエラー情報のファイルを作成
            $error_file_name = $ImportErrorCreateService->createImportError('受注取込エラー', $order_num['import_already'], $nowDate, ['注文番号']);
        }
        // order_importsテーブルにレコードが残っていれば処理を継続
        if($order_num['after_order_num'] == 0){
            throw new OrderImportException("新たに取り込みできる注文がありませんでした。", null, $order_num, $error_file_name);
        }
        // 出荷倉庫IDを更新
        $this->updateShippingBaseId();
        // 受注管理IDを採番
        $this->updateOrderControlId();
        // ordersとorder_itemsテーブルへ追加
        $this->createOrder();
        // order_import_historiesテーブルへ追加
        $this->createOrderImportHistory($import_type, null, $order_num, $error_file_name, $message);
        // 引当対象を取得
        $allocate_orders = $AllocateService->getAllocateOrder(null);
        // 商品引当処理(自動処理の適用でitemsとリレーションを張る必要があるので、先に商品引当だけここで実施)
        $ItemAllocateService->allocateItem($allocate_orders);
        // 自動処理を適用
        $AutoProcessApplyService->apply();
        // ポストカード追加処理
        $this->createPostcardRecord();
        return $order_num;
    }

    // order_importsへデータを追加
    public function createArrayImportData($order_create_data)
    {
        // テーブルをロック
        OrderImport::select()->lockForUpdate()->get();
        // 追加先のテーブルをクリア
        OrderImport::query()->delete();
        // 200件ごとにデータを分ける
        $chunks = array_chunk($order_create_data, 200);
        // 分割した分だけループ処理
        foreach($chunks as $chunk){
            // テーブルに追加
            OrderImport::insert($chunk);
        }
    }

    // 既に取込済みの受注を削除して、削除後の注文番号数を返す
    public function deleteImportedOrder()
    {
        // インポート済みの情報を格納する配列を初期化
        $import_already = [];
        // 削除処理前の注文数を取得
        $before_order_num = OrderImport::groupBy('order_control_id_seq')->select('order_control_id_seq')->get()->count();
        // ordersテーブルとorder_importsテーブルを注文番号と受注区分で結合
        $orders = Order::join('order_imports', function($join){
                            $join->on('order_imports.order_no', 'orders.order_no')
                                ->on('order_imports.order_category_id', 'orders.order_category_id');
                            })
                        ->groupBy('order_imports.order_no')
                        ->select('order_imports.order_no')
                        ->get();
        // 削除対象がいる場合
        if($orders->count() > 0){
            // 情報をセッションに格納
            $import_already = $orders->toArray();
        }
        // 既に取り込まれている注文をorder_importsテーブルから削除
        OrderImport::whereIn('order_no', $orders)->delete();
        // 削除処理後の注文数を取得
        $after_order_num = OrderImport::groupBy('order_control_id_seq')->select('order_control_id_seq')->get()->count();
        // 削除された注文数を取得(削除前 - 削除後)
        $delete_order_num = $before_order_num - $after_order_num;
        return compact('before_order_num', 'after_order_num', 'delete_order_num', 'import_already');
    }

    // 出荷倉庫IDを更新
    public function updateShippingBaseId()
    {
        // 都道府県名でテーブルを結合して、prefecturesの出荷倉庫IDでorder_importsの出荷倉庫IDを更新する
        DB::statement("
            UPDATE order_imports
            JOIN prefectures ON prefectures.prefecture_name = order_imports.ship_province_name
            SET order_imports.shipping_base_id = prefectures.shipping_base_id
        ");
    }

    // 受注管理IDを採番
    public function updateOrderControlId()
    {
        // 受注管理IDの先頭10桁(先頭固定BJを含む)に使用する文字列をランダムで生成し、既に使用されていないか確認する
        // 採番が終わったかを判定する変数を初期化
        $check = false;
        // $checkがtrueになるまでループ処理
        while(!$check){
            // 文字列を生成
            $order_control_id_head = 'BJ'.Str::random(9);
            // LIKE検索で生成した文字列をordersテーブルでカウント
            $count = Order::where('order_control_id', 'LIKE', '%'.$order_control_id_head.'%')->count();
            // countが0の場合
            if($count === 0){
                // 存在していないので、trueをセット（番号が決まったので処理を抜ける）
                $check = true;
            }
        }
        // 重複を取り除いた受注管理ID採番用を取得
        $orders = OrderImport::select('order_control_id_seq')->distinct()->get();
        // 受注管理IDの連番で使用する変数をセット
        $count = 0;
        // 注文番号の分だけループ処理
        foreach($orders as $order){
            // 受注管理IDを採番
            $count++;
            $order_control_id = $order_control_id_head . sprintf('%05d', $count);
            // 受注管理IDを更新
            OrderImport::where('order_control_id_seq', $order->order_control_id_seq)->update([
                'order_control_id' => $order_control_id,
            ]);
        }
    }

    // ordersとorder_itemsテーブルへ追加
    public function createOrder()
    {
        // ordersテーブルに追加する情報を取得
        $create_order = OrderImport::createTargetListForOrder(OrderImport::query())->get();
        // 重複を取り除きコレクションを配列に変換して取得
        $create_order_unique = collect($create_order)->unique()->toArray();
        // ordersテーブルに追加
        Order::upsert($create_order_unique, 'order_id');
        // order_itemsテーブルに追加する情報を取得
        $create_order_item = OrderImport::createTargetListForOrderItem(OrderImport::query())->get()->toArray();
        // order_itemsテーブルに追加
        OrderItem::upsert($create_order_item, 'order_item_id');
    }

    // order_import_historiesテーブルへ追加
    public function createOrderImportHistory($import_type, $import_info, $order_num, $error_file_name, $message)
    {
        // 追加
        OrderImportHistory::create([
            'import_type'       => $import_type,
            'import_file_name'  => isset($import_info['original_file_name']) ? $import_info['original_file_name'] : null,
            'all_order_num'     => is_null($order_num) ? null : $order_num['before_order_num'],
            'import_order_num'  => is_null($order_num) ? null : $order_num['after_order_num'],
            'delete_order_num'  => is_null($order_num) ? null : $order_num['delete_order_num'],
            'error_file_name'   => $error_file_name,
            'message'           => $message,
        ]);
    }

    // 配送方法を更新
    public function updateShippingMethod()
    {
        // 今回取り込んだ受注で配送方法がNullの受注の配送方法を更新(配送方法が自動処理で更新されなかった時用)
        Order::join('order_imports', 'order_imports.order_control_id', 'orders.order_control_id')
                ->whereNull('orders.shipping_method_id')
                ->update(['orders.shipping_method_id' => ShippingMethodEnum::YAMATO_NEKOPOS_ID]);
    }

    // 表示するメッセージを作成
    public function createDispMessage($result)
    {
        // 固定のメッセージをセット
        $message = $result['before_order_num'].'件中'.$result['after_order_num'].'件の受注データを取り込みしました。';
        // 削除された件数があれば、メッセージを追加
        if($result['delete_order_num'] > 0){
            $message .= '<br>取込不可：'.$result['delete_order_num'].'件';
        }
        return with([
            'type'      => $result['delete_order_num'] > 0 ? 'warning' : 'success',
            'message'   => $message,
        ]);
    }

    // ポストカード追加処理
    public function createPostcardRecord()
    {
        // 今回取り込んだ注文で支払金額を1,000円で割り、商の結果(postcard_amount) = ポストカード追加枚数として取得(上限を15枚としている)
        $orders = OrderImport::join('orders', 'orders.order_control_id', 'order_imports.order_control_id')
                        ->select(
                            'orders.order_control_id',
                            'orders.payment_amount',
                            DB::raw('LEAST(FLOOR(orders.payment_amount / 1000), 15) as postcard_amount')
                        )
                        ->groupBy('orders.order_control_id')
                        ->get();
        // 追加するポストカードの情報を格納する配列を初期化
        $data = [];
        // 注文の分だけループ処理
        foreach($orders as $order){
            // ポストカード追加枚数が0の場合
            if($order->postcard_amount === 0){
                // 次のループ処理へ
                continue;
            }
            // 各数字の出現回数を格納する配列を初期化
            $count_per_number = [];
            // ポストカードの追加枚数の分だけループ処理
            for($i = 0; $i < $order->postcard_amount; $i++){
                do {
                    // 1〜15のランダムな数値
                    $random = rand(1, 15);
                // 同じ数が2回以上なら再抽選
                } while (($count_per_number[$random] ?? 0) >= 2);
                // カウントを増やす
                $count_per_number[$random] = ($count_per_number[$random] ?? 0) + 1;
            }
            // ポストカードの種類の分だけループ処理
            foreach($count_per_number as $number => $quantity){
                // 追加する商品を取得
                $item = Item::getSpecifyByItemCode($number)->first();
                // レコードを追加
                OrderItem::create([
                    'order_control_id'      => $order->order_control_id,
                    'is_item_allocated'     => 0,
                    'is_stock_allocated'    => 0,
                    'order_item_code'       => $item->item_code,
                    'order_item_name'       => $item->item_name,
                    'allocated_item_id'     => $item->item_id,
                    'order_quantity'        => $quantity,
                    'is_auto_process_add'   => 1,
                ]);
            }
        }
    }
}