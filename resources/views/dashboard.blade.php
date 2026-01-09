<x-app-layout>
    <div class="grid grid-cols-12 gap-3">
        <div class="col-span-12 grid grid-cols-12 gap-3">
            <x-dashboard.info-div label="有給保有日数" :value="$employee->paid_leave->paid_leave_granted_days" formatType="days" />
            <x-dashboard.info-div label="残日数" :value="$employee->paid_leave->paid_leave_remaining_days" formatType="days" />
            <x-dashboard.info-div label="取得日数" :value="$employee->paid_leave->paid_leave_used_days" formatType="days" />
        </diiv>
        <div class="col-span-12 grid grid-cols-12 gap-3">
            <x-dashboard.info-div label="義務日数" :value="$employee->statutory_leave->statutory_leave_days" formatType="days" />
            <x-dashboard.info-div label="義務残日数" :value="$employee->statutory_leave->statutory_leave_remaining_days" formatType="days" />
            <x-dashboard.info-div label="義務期限日" :value="$employee->statutory_leave->statutory_leave_expiration_date" formatType="date" />
        </diiv>
    </diiv>
</x-app-layout>