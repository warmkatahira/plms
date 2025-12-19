<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            BaseSeeder::class,
            CompanySeeder::class,
            UserSeeder::class,
            PrefectureSeeder::class,
            DeliveryCompanySeeder::class,
            ShippingMethodSeeder::class,
            EHidenVersionSeeder::class,
            BaseShippingMethodSeeder::class,
            ShipperSeeder::class,
            //StockSeeder::class,
            //SetItemSeeder::class,
            //SetItemDetailSeeder::class,
            StockHistoryCategorySeeder::class,
            MallSeeder::class,
            OrderCategorySeeder::class,
            //ItemMallSeeder::class,
            //SetItemMallSeeder::class,
            ApiActionSeeder::class,
            ApiStatusSeeder::class,
            AutoProcessSeeder::class,
            ItemSeeder::class,
        ]);
    }
}
