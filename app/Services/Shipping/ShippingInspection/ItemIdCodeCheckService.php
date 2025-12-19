<?php

namespace App\Services\Shipping\ShippingInspection;

// モデル
use App\Models\Item;
// その他
use Carbon\CarbonImmutable;
// 列挙
use App\Enums\SystemEnum;

class ItemIdCodeCheckService
{
    // 検品対象の商品か確認し、問題なければ検品数をカウントアップ
    public function check($request)
    {
        // セッションの中身を配列にセット
        $progress = session('progress');
        // セッションを初期化
        session(['is_inspectable' => false]);               // 検品可能であるかを判断
        session(['inspection' => false]);                   // 検品できたか判断
        session(['item_id' => null]);                       // 検品したitem_id
        session(['inspection_quantity' => null]);           // カウントアップした後の検品数
        session(['inspection_complete' => false]);          // 検品数をカウントアップした商品の検品が完了したか判断
        session(['inspection_complete_order' => false]);    // 受注内の全ての商品で検品が完了しているか判断
        session(['error_message' => null]);                 // エラーメッセージを格納
        // 検品対象の商品があるかチェック
        $this->checkTarget($progress, $request->item_id_code);
        // 検品可能な商品である場合
        if(session('is_inspectable')){
            // 検品数をカウントアップ
            $this->updateInspectionQuantity($progress);
        }
        // エラーがあったか確認(inspectionがtrueであれば、検品できているので、nullを返す)
        session(['error_info' => session('inspection') ? null : $this->checkError($request->item_id_code)]);
    }

    // 検品対象の商品があるかチェック
    public function checkTarget($progress, $item_id_code)
    {
        // 配列の分だけループ処理
        foreach($progress as $key => $value){
            // JANコードが一致しない場合
            if($value['item_jan_code'] !== $item_id_code){
                // 次のループ処理へ
                continue;
            }
            // 特定した商品IDを取得
            session(['item_id' => $value['item_id']]);
            // JANコードが一致しているかつ、未検品数が残っている場合
            if((int)$value['total_ship_quantity'] > (int)$value['inspection_quantity']){
                // フラグをtrueに変更
                session(['is_inspectable' => true]);
                break;
            }
        }
    }

    // 検品数をカウントアップ
    public function updateInspectionQuantity($progress)
    {
        // 検品数を+1
        $progress[session('item_id')]['inspection_quantity'] = (int)$progress[session('item_id')]['inspection_quantity'] + 1;
        // 出荷数 = 検品数であれば、検品完了なのでtrueにする
        $progress[session('item_id')]['inspection_complete'] = $progress[session('item_id')]['inspection_quantity'] == $progress[session('item_id')]['total_ship_quantity'] ? true : false;
        // 検品できたので、trueにする
        session(['inspection' => true]);
        // セッションへ戻す
        session(['progress' => $progress]);
        // テーブル更新に使用する情報を格納
        session(['inspection_quantity' => $progress[session('item_id')]['inspection_quantity']]);
        session(['inspection_complete' => $progress[session('item_id')]['inspection_complete']]);
        // 受注内の全ての商品で検品が完了しているか確認
        session(['inspection_complete_order' => $this->checkInspectionCompleteOrder($progress)]);
    }

    // 受注内の全ての商品で検品が完了しているか確認
    public function checkInspectionCompleteOrder($progress)
    {
        // 配列の分だけループ処理
        foreach($progress as $key => $value){
            // 1つでもfalseがあれば、検品が完了していないので、falseを返す
            if($value['inspection_complete'] == false) {
                return false;
                break;
            }
        }
        // 全て完了しているので、trueを返す
        return true;
    }

    // エラーがあったか確認
    public function checkError($item_id_code)
    {
        // item_id_codeを使って商品マスタから情報を取得
        $item = Item::getSpecifyByItemJanCode($item_id_code)->first();
        // 商品が見つけられていない場合
        if(is_null($item)){
            return [
                'message'                   => '商品マスタに存在しない商品です',
                'item_id_code'              => $item_id_code,
                'item_name'                 => '-',
                'item_image_file_name'      => SystemEnum::DEFAULT_IMAGE_FILE_NAME,
            ];
        }
        // 商品があり検品対象ではあるが、検品ができていない場合
        if(!is_null(session('item_id'))){
            return [
                'message'                   => '検品が完了している商品です',
                'item_id_code'              => $item_id_code,
                'item_name'                 => $item->item_name,
                'item_image_file_name'      => $item->item_image_file_name,
            ];
        }
        return [
            'message'                   => '検品対象外の商品です',
            'item_id_code'              => $item_id_code,
            'item_name'                 => $item->item_name,
            'item_image_file_name'      => $item->item_image_file_name,
        ];
    }
}