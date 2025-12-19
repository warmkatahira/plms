<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="shipping_work_end_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">出荷予定日</th>
                    <th class="font-thin py-1 px-2 text-center">出荷倉庫</th>
                    <th class="font-thin py-1 px-2 text-center">出荷グループ名</th>
                    <th class="font-thin py-1 px-2 text-center">出荷完了対象件数<i class="tippy_shipping_work_end_target_count las la-info-circle la-lg ml-1"></i></th>
                    <th class="font-thin py-1 px-2 text-center">出荷完了対象外件数<i class="tippy_not_shipping_work_end_target_count las la-info-circle la-lg ml-1"></i></th>
                    <th class="font-thin py-1 px-2 text-center">出荷完了割合</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($shippingGroups as $shipping_group)
                    <tr class="text-left cursor-default whitespace-nowrap">
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <button type="button" class="btn shipping_work_end_enter bg-btn-enter text-white py-1 px-2" data-shipping-group-id="{{ $shipping_group->shipping_group_id }}">出荷完了</button>
                            </div>
                        </td>
                        <td class="py-1 px-2 border estimated_shipping_date">{{ CarbonImmutable::parse($shipping_group->estimated_shipping_date)->isoFormat('YYYY年MM月DD日(ddd)') }}</td>
                        <td class="py-1 px-2 border shipping_base_name">{{ $shipping_group->base->base_name }}</td>
                        <td class="py-1 px-2 border shipping_group_name">{{ $shipping_group->shipping_group_name }}</td>
                        <td class="py-1 px-2 border text-right completed_orders_count">{{ number_format($shipping_group->completed_orders_count) }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($shipping_group->incomplete_orders_count) }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format(($shipping_group->completed_orders_count / ($shipping_group->completed_orders_count + $shipping_group->incomplete_orders_count)) * 100) . ' %' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<form method="POST" action="{{ route('shipping_work_end.enter') }}" id="shipping_work_end_form" class="m-0">
    @csrf
    <input type="hidden" id="shipping_group_id" name="shipping_group_id">
</form>