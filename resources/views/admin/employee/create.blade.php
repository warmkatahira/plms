<x-app-layout>
    <div class="flex flex-row gap-10 mt-5">
        <form method="POST" action="{{ route('employee_create.create') }}" id="employee_form">
            @csrf
            <div class="flex flex-col border border-gray-400 divide-y divide-gray-400">
                <x-form.switch-boolean label="ステータス" id="status" name="status" label0="無効" label1="有効" :value="null" required="true" />
                <x-form.select label="営業所名" id="base_id" name="base_id" :value="null" :items="$bases" optionValue="base_id" optionText="base_name" required="true" />
                <x-form.input type="tel" label="従業員番号" id="employee_no" name="employee_no" :value="null" required="true" />
                <x-form.input type="text" label="氏名" id="user_name" name="user_name" :value="null" required="true" />
                <x-form.input type="text" label="ユーザーID" id="user_id" name="user_id" :value="null" required="true" />
                <x-form.switch-boolean label="義務残日数自動更新" id="is_auto_update_statutory_leave_remaining_days" name="is_auto_update_statutory_leave_remaining_days"  label1="有効" label0="無効" :value="null" required="true" />
            </div>
            <button type="button" id="employee_create_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-3"><i class="las la-check la-lg mr-1"></i>追加</button>
        </form>
    </div>
</x-app-layout>
@vite(['resources/js/admin/employee/employee.js'])