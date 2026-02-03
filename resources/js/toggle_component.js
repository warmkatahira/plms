// ルート詳細の表示/非表示のボタンが押下されたら
$('.route_toggle_components_btn').on("click",function(){
    // 要素を取得
    const $btn = $(this);
    const $componentRow = $btn.closest('tr').next('.route_detail_components');
    // hiddenクラスを変更
    $componentRow.toggleClass('hidden');
    // hiddenクラスを持っている/持っていないで処理を分岐
    if($componentRow.hasClass('hidden')){
        $btn.html('ルート詳細を表示')
            .removeClass('bg-btn-close')
            .addClass('bg-btn-open');
    }else{
        $btn.html('ルート詳細を非表示')
            .removeClass('bg-btn-open')
            .addClass('bg-btn-close');
    }
});