<div class="disable_scrollbar flex flex-grow overflow-scroll">
    <div class="employee_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    @can('admin_check')
                        <th class="font-thin py-1 px-2 text-center">操作</th>
                    @endcan
                    <th class="font-thin py-1 px-2 text-center">ステータス</th>
                    <th class="font-thin py-1 px-2 text-center">営業所</th>
                    <th class="font-thin py-1 px-2 text-center">社員CD</th>
                    <th class="font-thin py-1 px-2 text-center">社員名</th>
                    <th class="font-thin py-1 px-2 text-center">ID</th>
                    <th class="font-thin py-1 px-2 text-center">保有日数</th>
                    <th class="font-thin py-1 px-2 text-center">残日数</th>
                    <th class="font-thin py-1 px-2 text-center">取得日数</th>
                    <th class="font-thin py-1 px-2 text-center">1日あたりの<br>時間数</th>
                    <th class="font-thin py-1 px-2 text-center">半日あたりの<br>時間数</th>
                    <th class="font-thin py-1 px-2 text-center">義務残日数<br>自動更新</th>
                    <th class="font-thin py-1 px-2 text-center">義務期限日</th>
                    <th class="font-thin py-1 px-2 text-center">義務日数</th>
                    <th class="font-thin py-1 px-2 text-center">義務残日数</th>
                    <th class="font-thin py-1 px-2 text-center">最終更新日時</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($employees as $employee)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group @if(!$employee->status) bg-common-disabled @endif">
                        @can('admin_check')
                            <td class="py-1 px-2 border">
                                <div class="flex flex-row gap-5">
                                    <a href="{{ route('employee_update.index', ['user_no' => $employee->user_no]) }}" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                                </div>
                            </td>
                        @endcan
                        <td class="py-1 px-2 border text-center">
                            <x-list.status :value="$employee->status" label1="有効" label0="無効" />
                        </td>
                        <td class="py-1 px-2 border">{{ $employee->base->base_name }}</td>
                        <td class="py-1 px-2 border text-center">{{ $employee->employee_no }}</td>
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row items-center pr-10">
                                <img class="profile_image_normal image_fade_in_modal_open" src="{{ asset('storage/profile_images/'.$employee->profile_image_file_name) }}">
                                <span class="pl-2">{{ $employee->user_name }}</span>
                            </div>
                        </td>
                        <td class="py-1 px-2 border">{{ $employee->user_id }}</td>
                        <x-admin.employee.info :value="$employee->paid_leave_granted_days" digit="1" format="number" />
                        <x-admin.employee.info :value="$employee->paid_leave_remaining_days" digit="1" format="number" />
                        <x-admin.employee.info :value="$employee->paid_leave_used_days" digit="1" format="number" />
                        <x-admin.employee.info :value="$employee->daily_working_hours" digit="2" format="number" />
                        <x-admin.employee.info :value="$employee->half_day_working_hours" digit="2" format="number" />
                        <td class="py-1 px-2 border text-center">
                            <x-list.status :value="$employee->is_auto_update_statutory_leave_remaining_days" label1="有効" label0="無効" />
                        </td>
                        <x-admin.employee.info :value="$employee->statutory_leave_expiration_date" format="date" />
                        <x-admin.employee.info :value="$employee->statutory_leave_days" digit="1" format="number" />
                        <x-admin.employee.info :value="$employee->statutory_leave_remaining_days" digit="1" format="number" />
                        <td class="py-1 px-2 border">{{ CarbonImmutable::parse($employee->updated_at)->isoFormat('YYYY年MM月DD日(ddd) HH時mm分ss秒').'('.CarbonImmutable::parse($employee->updated_at)->diffForHumans().')' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>