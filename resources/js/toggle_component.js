// 構成品表示/非表示のボタンが押下されたら
$('.toggle_components_btn').on("click",function(){
    // 要素を取得
    const $btn = $(this);
    const $componentRow = $btn.closest('tr').next('.order_item_components');
    // hiddenクラスを変更
    $componentRow.toggleClass('hidden');
    // hiddenクラスを持っている/持っていないで処理を分岐
    if($componentRow.hasClass('hidden')){
        $btn.html('<i class="las la-plus"></i>')
            .removeClass('bg-btn-close')
            .addClass('bg-btn-open');
    }else{
        $btn.html('<i class="las la-minus"></i>')
            .removeClass('bg-btn-open')
            .addClass('bg-btn-close');
    }
});