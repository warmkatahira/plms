<x-app-layout>
    <div class="grid grid-cols-12 gap-5 mt-5">
        <x-menu.button route="api_history.index" title="API履歴" content="" />
        <x-menu.button route="makeshop_order.get_order" title="注文情報取得" content="メイクショップから注文情報を取得する" />
        <x-menu.button route="makeshop_order.order_import" title="注文情報取込" content="メイクショップから注文情報を取得し、システムに取り込みます" />
        <x-menu.button route="makeshop_item.update_image" title="商品画像更新" content="メイクショップと紐付いている商品の画像を取得・更新します" />
        <x-menu.button route="makeshop_stock.update_stock" title="在庫更新" content="システム在庫数をモールに更新します" />
        <x-menu.button route="makeshop_sample.getShopDeliverySetting" title="getShopDeliverySetting" content="" />
        <x-menu.button route="makeshop_sample.searchProductQuantity" title="searchProductQuantity" content="" />
        <x-menu.button route="makeshop_sample.updateOrderAttribute" title="updateOrderAttribute" content="" />
    </div>
</x-app-layout>