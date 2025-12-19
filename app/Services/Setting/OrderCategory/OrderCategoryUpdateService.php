<?php

namespace App\Services\Setting\OrderCategory;

// モデル
use App\Models\OrderCategory;

class OrderCategoryUpdateService
{
    // 受注区分を更新
    public function updateOrderCategory($request)
    {
        // 受注区分を取得
        $order_category = OrderCategory::getSpecify($request->order_category_id)->first();
        // 受注区分を更新
        $order_category->update([
            'order_category_name'   => $request->order_category_name,
            'mall_id'               => $request->mall_id,
            'shipper_id'            => $request->shipper_id,
            'nifuda_product_name_1' => $request->nifuda_product_name_1,
            'nifuda_product_name_2' => $request->nifuda_product_name_2,
            'nifuda_product_name_3' => $request->nifuda_product_name_3,
            'nifuda_product_name_4' => $request->nifuda_product_name_4,
            'nifuda_product_name_5' => $request->nifuda_product_name_5,
            'app_id'                => $request->app_id,
            'access_token'          => $request->access_token,
            'api_key'               => $request->api_key,
            'sort_order'            => $request->sort_order,
        ]);
    }
}