<x-app-layout>
    <div class="flex flex-row my-3">
        <x-system-admin.user.operation-div />
    </div>
    <div class="flex flex-row gap-x-5 items-start">
        <x-system-admin.working_hour.list :workingHours="$daily_working_hours" />
        <x-system-admin.working_hour.list :workingHours="$half_day_working_hours" />
    </div>
</x-app-layout>