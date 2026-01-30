<form method="POST"
      action="{{ $form_mode === 'create'
                    ? route('vehicle_create.create')
                    : route('vehicle_update.update') }}"
      id="vehicle_form">
    @csrf
    <div class="flex flex-col border border-gray-400 divide-y divide-gray-400">
        <x-form.switch-boolean label="利用可否" id="is_active" name="is_active" label0="利用不可" label1="利用可" :value="$form_mode === 'update' ? $vehicle->is_active : null" required="true" />
        <x-form.select label="車両区分" id="vehicle_type_id" name="vehicle_type_id" :value="$form_mode === 'update' ? $vehicle->vehicle_type_id : null" :items="$vehicle_types" optionValue="vehicle_type_id" optionText="vehicle_type" required="true" />
        <x-form.select label="車両種別" id="vehicle_category_id" name="vehicle_category_id" :value="$form_mode === 'update' ? $vehicle->vehicle_category_id : null" :items="$vehicle_categories" optionValue="vehicle_category_id" optionText="vehicle_category" required="true" />
        <x-form.select label="所有者" id="owner" name="owner" :value="$form_mode === 'update' ? $vehicle->user_no : Auth::user()->user_no" :items="$users" optionValue="user_no" optionText="full_name" />
        <x-form.input type="text" label="車両名" id="vehicle_name" name="vehicle_name" :value="$form_mode === 'update' ? $vehicle->vehicle_name : null" required="true" />
        <x-form.input type="text" label="車両色" id="vehicle_color" name="vehicle_color" :value="$form_mode === 'update' ? $vehicle->vehicle_color : null" required="true" />
        <x-form.input type="tel" label="車両ナンバー" id="vehicle_number" name="vehicle_number" :value="$form_mode === 'update' ? $vehicle->vehicle_number : null" required="true" />
        <x-form.input type="tel" label="送迎可能人数" id="vehicle_capacity" name="vehicle_capacity" :value="$form_mode === 'update' ? $vehicle->vehicle_capacity : null" required="true" />
        <x-form.input type="text" label="車両メモ" id="vehicle_memo" name="vehicle_memo" :value="$form_mode === 'update' ? $vehicle->vehicle_memo : null" />
    </div>
    @if($form_mode === 'update')
        <input type="hidden" name="vehicle_id" value="{{ $vehicle->vehicle_id }}">
    @endif
    <button type="button" id="vehicle_{{ $form_mode }}_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-3"><i class="las la-check la-lg mr-1"></i>{{ $form_mode === 'create' ? '追加' : '更新' }}</button>
</form>