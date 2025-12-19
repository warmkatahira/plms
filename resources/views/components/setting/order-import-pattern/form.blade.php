<form method="POST"
      action="{{ $form_mode === 'create'
                    ? route('order_import_pattern_create.create')
                    : route('order_import_pattern_update.update') }}"
      id="order_import_pattern_form">
    @csrf
    <div class="flex flex-col border border-gray-400 divide-y divide-gray-400">
        <x-form.select 
            label="受注区分"
            id="order_category_id"
            name="order_category_id"
            :value="$form_mode === 'update' ? $order_import_pattern->order_category_id : null"
            :items="$order_categories"
            optionValue="order_category_id"
            optionText="order_category_name"
            required="true"
        />
        <x-form.input 
            type="text" 
            label="パターン名" 
            id="pattern_name" 
            name="pattern_name"
            :value="$form_mode === 'update' ? $order_import_pattern->pattern_name : null"
            required="true" 
        />
        <x-form.input 
            type="text" 
            label="説明" 
            id="pattern_description" 
            name="pattern_description"
            :value="$form_mode === 'update' ? $order_import_pattern->pattern_description : null"
        />
        <x-form.select-array 
            label="カラム取得方法" 
            id="column_get_method" 
            name="column_get_method"
            :items="$column_get_methods"
            :value="$form_mode === 'update' ? $order_import_pattern->column_get_method : null"
            required="true"
            tippy="tippy_column_get_method"
        />
    </div>
    <div class="flex flex-col border border-gray-400 divide-y divide-gray-400 mt-5">
        <div class="flex flex-col bg-black text-white px-3 sticky top-0 z-50 py-2.5">
            <p class="text-center pb-1 w-full">カラム設定</p>
            <p class="text-xs">※カラム同士を結合する場合は「&&」でつなげて下さい。</p>
            <p class="text-xs">※値がある方を優先順位をつけて設定する場合は「||」でつなげて下さい。</p>
            <p class="text-xs">※固定値を紐付ける場合は、固定値のチェックを「ON」にして値を入力して下さい。</p>
        </div>
        @foreach($sysetm_column_mappings as $system_column_en => $sysetm_column_jp)
            @php
                // 値を格納する変数を初期化
                $value = null;
                // 固定値かどうかを判断する変数を初期化
                $is_fixed = false;
                // 更新の場合
                if($form_mode === 'update') {
                    // 受注取込パターン詳細の分だけループ処理
                    foreach($order_import_pattern->order_import_pattern_details as $order_import_pattern_detail){
                        // システムカラム名が受注取込パターン詳細のシステムカラム名と一致した場合
                        if($order_import_pattern_detail->system_column_name === $system_column_en){
                            // 前から順に値があるカラムから取得
                            $value =
                                $order_import_pattern_detail->order_column_name ??
                                $order_import_pattern_detail->order_column_index ??
                                $order_import_pattern_detail->fixed_value;
                            // 固定値がある場合
                            $is_fixed = !is_null($order_import_pattern_detail->fixed_value);
                            break;
                        }
                    }
                }
            @endphp
            <x-setting.order-import-pattern.input
                type="text"
                :label="$sysetm_column_jp"
                :id="$system_column_en"
                :name="$system_column_en"
                :value="$value"
                :isFixed="$is_fixed"
                :required="in_array($system_column_en, $required_system_columns)"
            />
        @endforeach
    </div>
    @if($form_mode === 'update')
        <input type="hidden" name="order_import_pattern_id" value="{{ $order_import_pattern->order_import_pattern_id }}">
    @endif
    <button type="button"
            class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-3"
            id="order_import_pattern_{{ $form_mode }}_enter">
        <i class="las la-check la-lg mr-1"></i>
        {{ $form_mode === 'create' ? '追加' : '更新' }}
    </button>
</form>