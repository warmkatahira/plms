<x-app-layout>
    <x-page-back :url="session('back_url_1')" />
    <div class="flex flex-row gap-10 mt-5">
        <form method="POST" action="{{ route('employee_update.update') }}" id="employee_form">
            @csrf
            <div class="flex flex-col border border-gray-400 divide-y divide-gray-400">
                <x-form.switch-boolean label="ステータス" id="status" name="status" label0="無効" label1="有効" :value="$employee->status" required="true" />
                <x-form.select label="営業所名" id="base_id" name="base_id" :value="$employee->base_id" :items="$bases" optionValue="base_id" optionText="base_name" required="true" />
                <x-form.input type="tel" label="従業員番号" id="employee_no" name="employee_no" :value="$employee->employee_no" required="true" />
                <x-form.input type="text" label="氏名" id="user_name" name="user_name" :value="$employee->user_name" required="true" />
                <x-form.switch-boolean label="義務残日数自動更新" id="is_auto_update_statutory_leave_remaining_days" name="is_auto_update_statutory_leave_remaining_days"  label1="有効" label0="無効" :value="$employee->is_auto_update_statutory_leave_remaining_days" required="true" />
            </div>
            <input type="hidden" name="user_no" value="{{ $employee->user_no }}">
            <button type="button" id="employee_update_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-3"><i class="las la-check la-lg mr-1"></i>追加</button>
        </form>
    </div>
</x-app-layout>
@vite(['resources/js/admin/employee/employee.js'])