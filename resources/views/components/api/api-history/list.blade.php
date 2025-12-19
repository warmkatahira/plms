<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="item_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">実行日時</th>
                    <th class="font-thin py-1 px-2 text-center">モール</th>
                    <th class="font-thin py-1 px-2 text-center">実行内容</th>
                    <th class="font-thin py-1 px-2 text-center">ステータス</th>
                    <th class="font-thin py-1 px-2 text-center">エラーファイル</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($apiHistories as $api_history)
                    <tr class="text-left cursor-default whitespace-nowrap">
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($api_history->updated_at)->isoFormat('Y年MM月DD日(ddd) HH:mm:ss') }}</td>
                        <td class="py-1 px-2 border">
                            <img src="{{ asset('storage/mall_images/'.$api_history->mall->mall_image_file_name) }}" class="mall_image inline-block">
                        </td>
                        <td class="py-1 px-2 border">{{ $api_history->api_action->api_action_name }}</td>
                        <td class="py-1 px-2 border text-center">{{ $api_history->api_status->api_status_name }}</td>
                        <td class="py-1 px-2 border">
                            @if(!is_null($api_history->error_file_name))
                                <a href="{{ route('error_file_download.download', ['filename' => $api_history->error_file_name]) }}" class="text-center text-blue-500"><i class="las la-cloud-download-alt mr-1 la-lg"></i>ダウンロード</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>