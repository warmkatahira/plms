<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
// モデル
use App\Models\Shipper;

class ShipperSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Shipper::create([
            'shipper_company_name'  => '株式会社Bluing',
            'shipper_name'          => 'F-SQUARE',
            'shipper_postal_code'   => '340-0807',
            'shipper_address'       => '埼玉県八潮市新町66',
            'shipper_tel'           => '048-930-0011',
        ]);
    }
}
