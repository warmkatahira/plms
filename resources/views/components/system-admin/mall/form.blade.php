<form method="POST"
      action="{{ $form_mode === 'create'
                    ? route('mall_create.create')
                    : route('mall_update.update') }}"
      id="mall_form"
      enctype="multipart/form-data">
    @csrf
    <div class="flex flex-col border border-gray-400 divide-y divide-gray-400">
        <x-form.image-select label="モール画像" />
        <x-form.input type="text" label="モール名" id="mall_name" name="mall_name" :value="$form_mode === 'update' ? $mall->mall_name : null" required="true" />
    </div>
    @if($form_mode === 'update')
        <input type="hidden" name="mall_id" value="{{ $mall->mall_id }}">
    @endif
    <button type="button" id="mall_{{ $form_mode }}_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-3"><i class="las la-check la-lg mr-1"></i>{{ $form_mode === 'create' ? '追加' : '更新' }}</button>
</form>