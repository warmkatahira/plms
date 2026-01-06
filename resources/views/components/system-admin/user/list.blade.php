<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="user_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">ステータス</th>
                    <th class="font-thin py-1 px-2 text-center">営業所名</th>
                    <th class="font-thin py-1 px-2 text-center">従業員番号</th>
                    <th class="font-thin py-1 px-2 text-center">氏名</th>
                    <th class="font-thin py-1 px-2 text-center">ユーザーID</th>
                    <th class="font-thin py-1 px-2 text-center">義務残日数自動更新</th>
                    <th class="font-thin py-1 px-2 text-center">権限</th>
                    <th class="font-thin py-1 px-2 text-center">最終ログイン日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($users as $user)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group @if(!$user->status) bg-common-disabled @endif">
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <a href="{{ route('user_update.index', ['user_no' => $user->user_no]) }}" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                            </div>
                        </td>
                        <td class="py-1 px-2 border text-center">
                            @if($user->status)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold
                                            text-status-active-text bg-status-active-bg rounded-full">
                                    <span class="w-2 h-2 bg-status-active-dot rounded-full"></span>
                                    有効
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold
                                            text-status-inactive-text bg-status-inactive-bg rounded-full">
                                    <span class="w-2 h-2 bg-status-inactive-dot rounded-full"></span>
                                    無効
                                </span>
                            @endif
                        </td>
                        <td class="py-1 px-2 border">{{ $user->base->base_name }}</td>
                        <td class="py-1 px-2 border text-center">{{ $user->employee_no }}</td>
                        <td class="py-1 px-2 border min-w-[230px]">
                            <img class="profile_image_normal image_fade_in_modal_open" src="{{ asset('storage/profile_images/'.$user->profile_image_file_name) }}">
                            {{ $user->user_name }}
                        </td>
                        <td class="py-1 px-2 border">{{ $user->user_id }}</td>
                        <td class="py-1 px-2 border text-center">{{ $user->is_auto_update_statutory_leave_remaining_days_text }}</td>
                        <td class="py-1 px-2 border text-center">{{ $user->role->role_name }}</td>
                        <td class="py-1 px-2 border">
                            @if($user->last_login_at)
                                {{ CarbonImmutable::parse($user->last_login_at)->isoFormat('YYYY年MM月DD日(ddd) HH時mm分ss秒').'('.CarbonImmutable::parse($user->last_login_at)->diffForHumans().')' }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>