<form method="POST"
      action="{{ $form_mode === 'create'
                    ? route('order_category_create.create')
                    : route('order_category_update.update') }}"
      id="order_category_form">
    @csrf
    <div class="flex flex-row gap-5 items-start">
        <div class="flex flex-col border border-gray-400 divide-y divide-gray-400">
            <x-form.input type="text" label="受注区分名" id="order_category_name" name="order_category_name" :value="$form_mode === 'update' ? $order_category->order_category_name : null" required="true" />
            <x-form.select label="モール" id="mall_id" name="mall_id" :value="$form_mode === 'update' ? $order_category->mall_id : null" :items="$malls" optionValue="mall_id" optionText="mall_name" required="true" />
            <x-form.select label="荷送人" id="shipper_id" name="shipper_id" :value="$form_mode === 'update' ? $order_category->shipper_id : null" :items="$shippers" optionValue="shipper_id" optionText="shipper_name" required="true" />
            <x-form.input type="tel" label="荷札品名1" id="nifuda_product_name_1" name="nifuda_product_name_1" :value="$form_mode === 'update' ? $order_category->nifuda_product_name_1 : null" required="true" />
            <x-form.input type="tel" label="荷札品名2" id="nifuda_product_name_2" name="nifuda_product_name_2" :value="$form_mode === 'update' ? $order_category->nifuda_product_name_2 : null" />
            <x-form.input type="tel" label="荷札品名3" id="nifuda_product_name_3" name="nifuda_product_name_3" :value="$form_mode === 'update' ? $order_category->nifuda_product_name_3 : null" />
            <x-form.input type="tel" label="荷札品名4" id="nifuda_product_name_4" name="nifuda_product_name_4" :value="$form_mode === 'update' ? $order_category->nifuda_product_name_4 : null" />
            <x-form.input type="tel" label="荷札品名5" id="nifuda_product_name_5" name="nifuda_product_name_5" :value="$form_mode === 'update' ? $order_category->nifuda_product_name_5 : null" />
            <x-form.input type="tel" label="アプリID" id="app_id" name="app_id" :value="$form_mode === 'update' ? $order_category->app_id : null" />
            <x-form.input type="tel" label="アクセストークン" id="access_token" name="access_token" :value="$form_mode === 'update' ? $order_category->access_token : null" />
            <x-form.input type="tel" label="APIキー" id="api_key" name="api_key" :value="$form_mode === 'update' ? $order_category->api_key : null" />
            <x-form.input type="tel" label="並び順" id="sort_order" name="sort_order" :value="$form_mode === 'update' ? $order_category->sort_order : null" required="true" />
        </div>
        <div class="bg-theme-main w-[500px] p-3">
            <p class="mb-5 text-lg font-bold">荷札品名について</p>
            <div>
                以下の設定を行うと、受注データの内容を参照して印字します。<br><br>
                [注文番号]<br>
                →該当受注の注文番号を印字します。<br><br>
                [受注管理ID]<br>
                →該当受注の受注管理IDを印字します。<br><br>
                [商品カテゴリ1]<br>
                →該当受注で出荷する1つの商品の商品カテゴリ1を印字します。<br>
                　※選ばれる商品はランダムです<br><br>
                [商品カテゴリ2]<br>
                →該当受注で出荷する1つの商品の商品カテゴリ2を印字します。<br>
                　※選ばれる商品はランダムです<br><br>
                [合計出荷数]<br>
                →該当受注の合計出荷数を印字します。
            </div>
            <div class="mt-5 text-red-500 bg-yellow-200 font-bold p-3">
                ※注意<br>
                　①ヤマト運輸は品名枠の都合上、品名3までしか印字できません。
            </div>
        </div>
    </div>
    @if($form_mode === 'update')
        <input type="hidden" name="order_category_id" value="{{ $order_category->order_category_id }}">
    @endif
    <button type="button" id="order_category_{{ $form_mode }}_enter" class="btn bg-btn-enter p-3 text-white w-56 ml-auto mt-3"><i class="las la-check la-lg mr-1"></i>{{ $form_mode === 'create' ? '追加' : '更新' }}</button>
</form>