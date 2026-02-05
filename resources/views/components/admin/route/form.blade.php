<form method="POST"
      action="{{ $form_mode === 'create'
                    ? route('route_create.create')
                    : route('route_update.update') }}"
      id="route_form">
    @csrf
    <div class="flex flex-col border border-gray-400 divide-y divide-gray-400">
        <x-form.switch-boolean label="利用可否" id="is_active" name="is_active" label0="利用不可" label1="利用可" :value="$form_mode === 'update' ? $route->is_active : null" required="true" />
        <x-form.select label="ルート区分" id="route_type_id" name="route_type_id" :value="$form_mode === 'update' ? $route->route_type_id : null" :items="$route_types" optionValue="route_type_id" optionText="route_type" required="true" />
        <x-form.select label="車両種別" id="vehicle_category_id" name="vehicle_category_id" :value="$form_mode === 'update' ? $route->vehicle_category_id : null" :items="$vehicle_categories" optionValue="vehicle_category_id" optionText="vehicle_category" required="true" />
        <x-form.input type="text" label="ルート名" id="route_name" name="route_name" :value="$form_mode === 'update' ? $route->route_name : null" required="true" />
        <x-form.input type="tel" label="並び順" id="sort_order" name="sort_order" :value="$form_mode === 'update' ? $route->sort_order : null" required="true" />
    </div>
    @if($form_mode === 'update')
        <input type="hidden" name="route_id" value="{{ $route->route_id }}">
    @endif
    <button type="button" id="route_{{ $form_mode }}_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-3"><i class="las la-check la-lg mr-1"></i>{{ $form_mode === 'create' ? '追加' : '更新' }}</button>
</form>