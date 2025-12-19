<?php

namespace App\Services\Stock\ReceivingInspection;
// モデル
use App\Models\Item;
// その他
use Carbon\CarbonImmutable;

class ItemIdCodeCheckService
{
    // 商品マスタに存在するか確認し、問題なければ検品数をカウントアップ
    public function check($request)
    {
        // セッションを初期化
        session(['found' => false]);                        // 商品が見つかったか判断
        session(['add' => false]);                          // 新しい商品がスキャンされたかを判断
        session(['quantity' => 1]);                         // 今回スキャンした商品の数量を格納
        session(['error_message' => null]);                 // エラーメッセージを格納
        // 商品マスタからレコードを取得
        $item = $this->getItem($request->item_id_code);
        // 取得できている場合
        if(!is_null($item)){
            // 検品情報をカウント
            $this->countInspection($item);
        }
        return $item;
    }

    // 商品マスタからレコードを取得
    public function getItem($item_id_code)
    {
        // 商品JANコードを条件に商品マスタからレコードを取得
        $item = Item::where('item_jan_code', $item_id_code)->first();
        // レコードが取得できていない場合
        if(is_null($item)){
            session(['error_message' => '商品マスタに存在しない商品です。']);
        }
        return $item;
    }

    // 検品情報をカウント
    public function countInspection($item)
    {
        // セッションの中身を配列にセット
        $progress = session('progress');
        // 同じ商品が存在したか判定する変数
        $exsits = false;
        // 配列の分だけループ処理
        foreach($progress as $key => $value){
            // 同じ商品が存在すれば、数量を+1する
            if($value['item_id'] == $item->item_id){
                $exsits = true;
                session(['quantity' => (int)$progress[$key]['quantity'] + 1]);
                $progress[$key]['quantity'] = session('quantity');
                break;
            }
        }
        // 配列に同じ商品がなければ、配列に追加する
        if(!$exsits){
            array_push($progress, [
                'item_id'   => $item->item_id,
                'quantity'  => 1,
            ]);
            session(['add' => true]);
        }
        // セッションへ戻す
        session(['progress' => $progress]);
    }
}