<form method="POST"
      action="{{ $form_mode === 'create'
                    ? route('employee_create.create')
                    : route('employee_update.update') }}"
      id="employee_form">
    @csrf
    <div class="flex flex-col border border-gray-400 divide-y divide-gray-400">
        <x-form.switch-boolean label="ステータス" id="status" name="status" label0="無効" label1="有効" :value="$form_mode === 'update' ? $employee->status : null" required="true" />
        <x-form.select label="営業所名" id="base_id" name="base_id" :value="$form_mode === 'update' ? $employee->base_id : null" :items="$bases" optionValue="base_id" optionText="base_name" required="true" />
        <x-form.input type="tel" label="従業員番号" id="employee_no" name="employee_no" :value="$form_mode === 'update' ? $employee->employee_no : null" required="true" />
        <x-form.input type="text" label="氏名" id="user_name" name="user_name" :value="$form_mode === 'update' ? $employee->user_name : null" required="true" />
        @if($form_mode === 'create')
            <x-form.input type="tel" label="ユーザーID" id="user_id" name="user_id" :value="null" required="true" />
        @else
            <x-form.p label="ユーザーID" :value="$employee->user_id" grayedOut="true" />
        @endif
        @if($form_mode === 'create')
            <x-form.input type="tel" label="パスワード" id="password" name="password" :value="null" required="true" />
        @endif
        <x-form.input type="tel" label="保有日数" id="paid_leave_granted_days" name="paid_leave_granted_days" :value="$form_mode === 'update' ? $employee->paid_leave->paid_leave_granted_days : null" />
        <x-form.input type="tel" label="残日数" id="paid_leave_remaining_days" name="paid_leave_remaining_days" :value="$form_mode === 'update' ? $employee->paid_leave->paid_leave_remaining_days : null" />
        <x-form.input type="tel" label="取得日数" id="paid_leave_used_days" name="paid_leave_used_days" :value="$form_mode === 'update' ? $employee->paid_leave->paid_leave_used_days : null" />
        <x-form.select-array label="1日あたりの時間数" id="daily_working_hours" name="daily_working_hours" :items="$daily_working_hours" :value="$form_mode === 'update' ? $employee->paid_leave->daily_working_hours : null" />
        <x-form.select-array label="半日あたりの時間数" id="half_day_working_hours" name="half_day_working_hours" :items="$half_day_working_hours" :value="$form_mode === 'update' ? $employee->paid_leave->half_day_working_hours : null" />
        <x-form.switch-boolean label="義務残日数自動更新" id="is_auto_update_statutory_leave_remaining_days" name="is_auto_update_statutory_leave_remaining_days"  label1="有効" label0="無効" :value="$form_mode === 'update' ? $employee->is_auto_update_statutory_leave_remaining_days : null" />
        <x-form.input type="date" label="義務期限日" id="statutory_leave_expiration_date" name="statutory_leave_expiration_date" :value="$form_mode === 'update' ? $employee->statutory_leave->statutory_leave_expiration_date : null" />
        <x-form.input type="tel" label="義務日数" id="statutory_leave_days" name="statutory_leave_days" :value="$form_mode === 'update' ? $employee->statutory_leave->statutory_leave_days : null" />
        <x-form.input type="tel" label="義務残日数" id="statutory_leave_remaining_days" name="statutory_leave_remaining_days" :value="$form_mode === 'update' ? $employee->statutory_leave->statutory_leave_remaining_days : null" />
    </div>
    @if($form_mode === 'update')
        <input type="hidden" name="user_no" value="{{ $employee->user_no }}">
    @endif
    <button type="button" id="employee_{{ $form_mode }}_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-3"><i class="las la-check la-lg mr-1"></i>{{ $form_mode === 'create' ? '追加' : '更新' }}</button>
</form>