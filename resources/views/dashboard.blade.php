<x-app-layout>
    @if(Auth::user()->role_id === RoleEnum::USER)
        <div class="grid grid-cols-12 gap-3">
            <div class="col-span-12 grid grid-cols-12 gap-3">
                <x-dashboard.info-div label="有給保有日数" :value="$employee->paid_leave->paid_leave_granted_days" formatType="day" />
                <x-dashboard.info-div label="有給残日数" :value="$employee->paid_leave->paid_leave_remaining_days" formatType="day" />
                <x-dashboard.info-div label="有給取得日数" :value="$employee->paid_leave->paid_leave_used_days" formatType="day" />
            </diiv>
            <div class="col-span-12 grid grid-cols-12 gap-3">
                <x-dashboard.info-div label="義務の日数" :value="$employee->statutory_leave->statutory_leave_days" formatType="day" />
                <x-dashboard.info-div label="義務の残日数" :value="$employee->statutory_leave->statutory_leave_remaining_days" formatType="day" />
                <x-dashboard.info-div label="義務の期限" :value="$employee->statutory_leave->statutory_leave_expiration_date" formatType="date" />
            </diiv>
            <div class="col-span-12 grid grid-cols-12 gap-3">
                <x-dashboard.info-div label="1日あたりの時間数" :value="$employee->paid_leave->daily_working_hours" decimal="2" formatType="hour" />
                <x-dashboard.info-div label="半日あたりの時間数" :value="$employee->paid_leave->half_day_working_hours" decimal="2" formatType="hour" />
            </diiv>
        </diiv>
    @endif
</x-app-layout>