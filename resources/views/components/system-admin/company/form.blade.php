<form method="POST"
      action="{{ $form_mode === 'create'
                    ? route('company_create.create')
                    : route('company_update.update') }}"
      id="company_form">
    @csrf
    <div class="flex flex-col border border-gray-400 divide-y divide-gray-400">
        @if($form_mode === 'create')
            <x-form.input type="text" label="会社ID" id="company_id" name="company_id" :value="null" required="true" />
        @endif
        @if($form_mode === 'update')
            <x-form.p label="会社ID" :value="$company->company_id" />
        @endif
        <x-form.input type="text" label="会社名" id="company_name" name="company_name" :value="$form_mode === 'update' ? $company->company_name : null" required="true" />
        <x-form.input type="text" label="並び順" id="sort_order" name="sort_order" :value="$form_mode === 'update' ? $company->sort_order : null" required="true" />
    </div>
    @if($form_mode === 'update')
        <input type="hidden" name="company_id" value="{{ $company->company_id }}">
    @endif
    <button type="button" id="company_{{ $form_mode }}_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-3"><i class="las la-check la-lg mr-1"></i>{{ $form_mode === 'create' ? '追加' : '更新' }}</button>
</form>