<div class="flex flex-col gap-3">
    <div class="flex flex-col gap-3 bg-white p-3 border border-gray-400">
        <x-ride.ride-schedule.info-div label="ルート区分" :value="$route->route_type->route_type" />
        <x-ride.ride-schedule.info-div label="車両種別" :value="$route->vehicle_category->vehicle_category" />
        <x-ride.ride-schedule.info-div label="ルート名" :value="$route->route_name" />
        <x-ride.ride-schedule.info-div label="乗降場所数" :value="$route->route_details->count()" />
    </div>
    <form method="POST"
        action="{{ $form_mode === 'create'
                        ? route('ride_schedule_create.create')
                        : route('ride_schedule_update.update') }}"
        id="ride_schedule_form">
        @csrf
        <div class="flex flex-col border border-gray-400 divide-y divide-gray-400">
            <x-form.switch-boolean label="運行状況" id="is_active" name="is_active" label0="運行未定" label1="運行決定" :value="$form_mode === 'update' ? $ride->is_active : null" required="true" />
            <x-form.input type="text" label="送迎日" id="schedule_date" name="schedule_date" :value="$form_mode === 'update' ? $ride->schedule_date : null" required="true" />
            <x-form.select label="ドライバー" id="driver_user_no" name="driver_user_no" :value="$form_mode === 'update' ? $ride->driver_user_no : null" :items="$users" optionValue="user_no" optionText="full_name" />
            <x-form.select label="使用車両" id="use_vehicle_id" name="use_vehicle_id" :value="$form_mode === 'update' ? $ride->use_vehicle_id : null" :items="$vehicles" optionValue="vehicle_id" optionText="vehicle_name" />
            <x-form.input type="text" label="送迎メモ" id="ride_memo" name="ride_memo" :value="$form_mode === 'update' ? $ride->ride_memo : null" />
        </div>
        @if($form_mode === 'update')
            <input type="hidden" name="ride_id" value="{{ $ride->ride_id }}">
        @endif
        @if($form_mode === 'create')
            <input type="hidden" name="route_id" value="{{ $route->route_id }}">
        @endif
        <button type="button" id="ride_schedule_{{ $form_mode }}_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-3"><i class="las la-check la-lg mr-1"></i>{{ $form_mode === 'create' ? '追加' : '更新' }}</button>
    </form>
</div>