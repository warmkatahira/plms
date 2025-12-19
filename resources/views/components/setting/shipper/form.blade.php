<form method="POST"
      action="{{ $form_mode === 'create'
                    ? route('shipper_create.create')
                    : route('shipper_update.update') }}"
      id="shipper_form">
    @csrf
    <div class="flex flex-col border border-gray-400 divide-y divide-gray-400">
        <x-form.input type="text" label="荷送人会社名" id="shipper_company_name" name="shipper_company_name" :value="$form_mode === 'update' ? $shipper->shipper_company_name : null" required="true" />
        <x-form.input type="text" label="荷送人名" id="shipper_name" name="shipper_name" :value="$form_mode === 'update' ? $shipper->shipper_name : null" required="true" />
        <x-form.input type="text" label="荷送人郵便番号" id="shipper_postal_code" name="shipper_postal_code" :value="$form_mode === 'update' ? $shipper->shipper_postal_code : null" required="true" />
        <x-form.input type="text" label="荷送人住所" id="shipper_address" name="shipper_address" :value="$form_mode === 'update' ? $shipper->shipper_address : null" required="true" />
        <x-form.input type="text" label="荷送人電話番号" id="shipper_tel" name="shipper_tel" :value="$form_mode === 'update' ? $shipper->shipper_tel : null" required="true" />
        <x-form.input type="text" label="荷送人メールアドレス" id="shipper_email" name="shipper_email" :value="$form_mode === 'update' ? $shipper->shipper_email : null" />
        <x-form.input type="text" label="荷送人インボイス番号" id="shipper_invoice_no" name="shipper_invoice_no" :value="$form_mode === 'update' ? $shipper->shipper_invoice_no : null" />
    </div>
    @if($form_mode === 'update')
        <input type="hidden" name="shipper_id" value="{{ $shipper->shipper_id }}">
    @endif
    <button type="button" id="shipper_{{ $form_mode }}_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-3"><i class="las la-check la-lg mr-1"></i>{{ $form_mode === 'create' ? '追加' : '更新' }}</button>
</form>