import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // 共通
                'resources/js/app.js',
                'resources/css/app.css',
                'resources/sass/theme.scss',
                'resources/js/common.js',
                'resources/js/loading.js',
                'resources/js/search.js',
                'resources/js/file_select.js',
                'resources/js/search_date.js',
                'resources/sass/loading.scss',
                'resources/sass/navigation.scss',
                'resources/js/dropdown.js',
                'resources/js/image_fade_in.js',
                'resources/js/toggle_component.js',
                'resources/js/clipboard_copy.js',
                'resources/sass/dropdown.scss',
                'resources/sass/height_adjustment.scss',
                'resources/sass/welcome.scss',
                'resources/sass/common.scss',
                'resources/sass/clipboard_copy.scss',
                // 認証
                'resources/js/auth/register.js',
                // ダッシュボード
                'resources/js/dashboard/dashboard.js',
                'resources/sass/dashboard/dashboard.scss',
                // 受注
                'resources/js/order/order_import/order_import.js',
                'resources/js/order/order_mgt/order_mgt.js',
                'resources/js/order/order_detail/order_detail.js',
                // 出荷
                'resources/js/shipping/shipping_mgt/shipping_mgt.js',
                'resources/js/shipping/shipping_inspection/shipping_inspection.js',
                'resources/js/shipping/shipping_work_end/shipping_work_end.js',
                'resources/js/shipping/abc_analysis/abc_analysis.js',
                // 帳票
                'resources/sass/shipping/document/document_common.scss',
                'resources/sass/shipping/document/total_picking_list.scss',
                'resources/sass/shipping/document/delivery_note.scss',
                'resources/sass/shipping/document/kobetsu_picking_list.scss',
                // 商品
                'resources/js/item/item/item.js',
                'resources/js/item/set_item/set_item.js',
                'resources/js/item/item_mall_mapping/item_mall_mapping.js',
                'resources/js/item/item_upload/item_upload.js',
                // 在庫
                'resources/js/stock/stock/stock.js',
                'resources/js/stock/input_stock_operation/input_stock_operation.js',
                'resources/js/stock/receiving_inspection/receiving_inspection.js',
                // 設定
                'resources/js/setting/order_import_pattern/order_import_pattern.js',
                'resources/js/setting/shipping_base/shipping_base.js',
                'resources/js/setting/base_shipping_method/base_shipping_method.js',
                'resources/js/setting/shipper/shipper.js',
                'resources/js/setting/auto_process/auto_process.js',
                'resources/js/setting/auto_process/auto_process_condition.js',
                'resources/js/setting/order_category/order_category.js',
                // システム管理
                'resources/js/system_admin/base/base.js',
                'resources/js/system_admin/user/user.js',
                'resources/js/system_admin/company/company.js',
                'resources/js/system_admin/holiday/holiday.js',
                'resources/js/system_admin/mall/mall.js',
                // プロフィール
                'resources/js/profile/profile.js',
                'resources/sass/profile/profile.scss',
                'resources/sass/profile/profile_image.scss',
            ],
            refresh: true,
        }),
    ],
});