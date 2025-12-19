<?php

namespace App\Services\Setting\Shipper;

// モデル
use App\Models\Shipper;

class ShipperCreateService
{
    // 荷送人を追加
    public function createShipper($request)
    {
        // 荷送人を追加
        Shipper::create([
            'shipper_company_name'  => $request->shipper_company_name,
            'shipper_name'          => $request->shipper_name,
            'shipper_postal_code'   => $request->shipper_postal_code,
            'shipper_address'       => $request->shipper_address,
            'shipper_tel'           => $request->shipper_tel,
            'shipper_email'         => $request->shipper_email,
            'shipper_invoice_no'    => $request->shipper_invoice_no,
        ]);
    }
}