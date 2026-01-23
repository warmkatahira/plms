<x-app-layout>
    @if(Auth::user()->role_id === RoleEnum::USER)
        <div class="grid grid-cols-12 gap-3">
            <div class="col-span-12 md:hidden bg-white rounded-xl shadow-lg p-4">
                <p class="text-2xl font-bold text-gray-800 text-center">{{ Auth::user()->user_name }}<span class="text-base font-normal text-gray-400 pl-3">さん</span></p>
            </div>
            <div class="col-span-12 grid grid-cols-12 gap-3">
                <x-dashboard.info-div label="有給保有日数" :value="$employee->paid_leave->paid_leave_granted_days" format="day" />
                <x-dashboard.info-div label="有給残日数" :value="$employee->paid_leave->paid_leave_remaining_days" format="day" />
                <x-dashboard.info-div label="有給取得日数" :value="$employee->paid_leave->paid_leave_used_days" format="day" />
            </diiv>
            <div class="col-span-12 grid grid-cols-12 gap-3">
                <x-dashboard.info-div label="義務の日数" :value="$employee->statutory_leave->statutory_leave_days" format="day" />
                <x-dashboard.info-div label="義務の残日数" :value="$employee->statutory_leave->statutory_leave_remaining_days" format="day" />
                <x-dashboard.info-div label="義務の期限" :value="$employee->statutory_leave->statutory_leave_expiration_date" format="date" />
            </diiv>
            <div class="col-span-12 grid grid-cols-12 gap-3">
                <x-dashboard.info-div label="1日あたりの時間数" :value="$employee->paid_leave->daily_working_hours" digit="2" format="hour" />
                <x-dashboard.info-div label="半日あたりの時間数" :value="$employee->paid_leave->half_day_working_hours" digit="2" format="hour" />
            </diiv>
        </diiv>
    @endif
</x-app-layout>