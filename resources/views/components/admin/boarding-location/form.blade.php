<form method="POST"
      action="{{ $form_mode === 'create'
                    ? route('boarding_location_create.create')
                    : route('boarding_location_update.update') }}"
      id="boarding_location_form">
    @csrf
    <div class="flex flex-col border border-gray-400 divide-y divide-gray-400">
        <x-form.switch-boolean label="利用可否" id="is_active" name="is_active" label0="利用不可" label1="利用可" :value="$form_mode === 'update' ? $boarding_location->is_active : null" required="true" />
        <x-form.input type="text" label="場所名" id="location_name" name="location_name" :value="$form_mode === 'update' ? $boarding_location->location_name : null" required="true" />
        <x-form.input type="text" label="場所メモ" id="location_memo" name="location_memo" :value="$form_mode === 'update' ? $boarding_location->location_memo : null" />
        <x-form.input type="tel" label="並び順" id="sort_order" name="sort_order" :value="$form_mode === 'update' ? $boarding_location->sort_order : null" />
    </div>
    @if($form_mode === 'update')
        <input type="hidden" name="boarding_location_id" value="{{ $boarding_location->boarding_location_id }}">
    @endif
    <button type="button" id="boarding_location_{{ $form_mode }}_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-3"><i class="las la-check la-lg mr-1"></i>{{ $form_mode === 'create' ? '追加' : '更新' }}</button>
</form>