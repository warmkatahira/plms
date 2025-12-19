<?php

namespace App\Services\Setting\OrderCategory;

// モデル
use App\Models\OrderCategory;

class OrderCategoryCreateService
{
    // 受注区分を追加
    public function createOrderCategory($request)
    {
        // 受注区分を追加
        OrderCategory::create([
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