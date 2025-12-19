<x-document-layout>
    <div class="page-container">
        @php
            // 変数を初期化
            $order_count = 0;
        @endphp
        @foreach($orders as $order)
            <!-- 最初のページに余計なページがでないように、改ページをコントロールするためのカウント -->
            @php
                // 変数を初期化
                $total_ship_quantity = 0;
                // 受注をカウント
                $order_count++;
            @endphp
            <div style="{{ $order_count != 1 ? 'page-break-before: always; padding-top: 0mm;' : '' }}">
                <div class="flex flex-row justify-between">
                    <span>{!! DNS1D::getBarcodeSVG($order->order_control_id, 'C128', 0.8, 40, 'black') !!}</span>
                    <div class="flex flex-col">
                        <span class="text-center text-xl">個別ピッキングリスト</span>
                        <span class="text-center">{{ SystemEnum::getSystemTitle() }}</span>
                    </div>
                    <span>{!! DNS1D::getBarcodeSVG($order->order_control_id, 'C128', 0.8, 40, 'black') !!}</span>
                </div>
            </div>
            <!-- 注文概要 -->
            <div class="my-5 flex flex-row flex-wrap">
                @php
                    // 変数を初期化
                    $desired_delivery_date = '';
                    // 配送希望日がNull以外の場合
                    if(!is_null($order->desired_delivery_date)){
                        // フォーマットを変更
                        $desired_delivery_date = CarbonImmutable::parse($order->desired_delivery_date)->isoFormat('Y年MM月DD日(ddd)');
                    }
                @endphp
                <x-shipping.kobetsu-picking-list.info-div label="発行日時" :value="CarbonImmutable::now()->isoFormat('Y/MM/DD HH:mm:ss')" />
                <x-shipping.kobetsu-picking-list.info-div label="出荷グループ名" :value="$order->shipping_group?->shipping_group_name" />
                <x-shipping.kobetsu-picking-list.info-div label="注文番号" :value="$order->order_no" />
                <x-shipping.kobetsu-picking-list.info-div label="出荷管理番号" :value="$order->order_control_id" />
                <x-shipping.kobetsu-picking-list.info-div label="配送先名" :value="$order->ship_name" />
                <x-shipping.kobetsu-picking-list.info-div label="配送方法" :value="$order->delivery_company_and_shipping_method" />
                <x-shipping.kobetsu-picking-list.info-div label="配送希望日" :value="$desired_delivery_date" />
                <x-shipping.kobetsu-picking-list.info-div label="配送希望時間" :value="$order->desired_delivery_time" />
                <x-shipping.kobetsu-picking-list.info-div label="出荷作業メモ" :value="$order->shipping_work_memo" parentDivWidth="w-full" childSpanLabelWidth="w-1/6" childSpanValueWidth="w-5/6" />
            </div>
            <!-- 商品明細 -->
            <table class="mt-1 text-xs w-full">
                <thead>
                    <tr>
                        <th class="item_location bg-theme-sub font-thin text-center py-2 border border-black">ロケ</th>
                        <th class="item_jan_code bg-theme-sub font-thin text-center py-2 border border-black">商品JANコード</th>
                        <th class="item_name bg-theme-sub font-thin text-center py-2 border border-black">商品名</th>
                        <th class="ship_quantity bg-theme-sub font-thin text-center py-2 border border-black">数量</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->order_items as $order_item)
                        @foreach($order_item->order_item_components as $order_item_component)
                            @php
                                // 合計出荷数をカウント
                                $total_ship_quantity += $order_item_component->ship_quantity;
                            @endphp
                            <tr>
                                <td class="item_location text-center py-2 border border-black">{{ $item_locations[$order_item_component->allocated_component_item_id] ?? null }}</td>
                                <td class="item_jan_code text-center py-2 border border-black">{{ $order_item_component->item_jan_code_snapshot }}</td>
                                <td class="item_name py-2 border border-black pl-1">{{ $order_item_component->item_name_snapshot }}</td>
                                <td class="ship_quantity text-right py-2 border border-black pr-1">{{ number_format($order_item_component->ship_quantity) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3 flex flex-row justify-between">
                <div class="flex flex-col gap-3">
                    <!-- サイン欄 -->
                    <div class="flex flex-row gap-3">
                        <x-shipping.kobetsu-picking-list.sign-div label="ピッキング" />
                        <x-shipping.kobetsu-picking-list.sign-div label="検品" />
                        <x-shipping.kobetsu-picking-list.sign-div label="梱包" />
                    </div>
                </div>
                <!-- 合計数量 -->
                <div class="flex flex-col gap-1">
                    <div class="flex flex-col w-20">
                        <span class="text-center bg-theme-sub border border-black py-1">合計数量</span>
                        <span class="pr-1 text-right border-x border-b border-black py-1">{{ number_format($total_ship_quantity) }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-document-layout>
@vite(['resources/sass/shipping/document/kobetsu_picking_list.scss'])