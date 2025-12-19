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
                <div class="text-center">
                    <p class="text-2xl font-semibold">納品書</p>
                </div>
                <p class="mt-2">この度は、F-SQUAREをご利用いただき誠にありがとうございます。</p>
            </div>
            <!-- 注文概要 -->
            <div class="my-5 flex flex-row flex-wrap">
                <div class="flex flex-row justify-between w-full text-xs">
                    <div class="flex flex-col w-1/2">
                        <div class="flex flex-row">
                            <p class="w-1/5">注文日</p>
                            <p class="w-4/5">{{ CarbonImmutable::parse($order->order_date)->isoFormat('Y年MM月DD日') }}</p>
                        </div>
                        <div class="flex flex-row">
                            <p class="w-1/5">注文番号</p>
                            <p class="w-4/5">{{ $order->order_no }}</p>
                        </div>
                        <div class="flex flex-row">
                            <p class="w-1/5">注文者</p>
                            <p class="w-4/5">{{ $order->buyer_name . ' 様' }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col w-1/2">
                        <p>配送先</p>
                        <div class="ml-5">
                            <p>{{ $order->ship_name . ' 様' }}</p>
                            <p>{{ '〒'.$order->ship_postal_code }}</p>
                            <p>{{ $order->ship_address }}</p>
                            <p>{{ $order->ship_tel }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 商品明細 -->
            <table class="mt-1 text-xs w-full">
                <thead>
                    <tr>
                        <th class="item_name bg-theme-sub font-thin text-center py-2 border border-black">商品名</th>
                        <th class="ship_quantity bg-theme-sub font-thin text-center py-2 border border-black">数量</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->order_items as $order_item)
                        @foreach($order_item->order_item_components as $order_item_component)
                            @if($order_item_component->is_hide_on_delivery_note_snapshot === 0)
                                <tr>
                                    <td class="item_name py-2 border border-black pl-1">{{ $order_item_component->item_name_snapshot }}</td>
                                    <td class="ship_quantity text-right py-2 border border-black pr-1">{{ number_format($order_item_component->ship_quantity) }}</td>
                                </tr>
                            @endif
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        @endforeach
    </div>
</x-document-layout>
@vite(['resources/sass/shipping/document/delivery_note.scss'])