import start_loading from '../../loading';

// 出荷完了を押下したら
$('.shipping_work_end_enter').on("click",function(){
    // 出荷完了する出荷グループの情報を取得
    const estimated_shipping_date   = $(this).closest('tr').find('.estimated_shipping_date').text().trim();
    const shipping_base_name        = $(this).closest('tr').find('.shipping_base_name').text().trim();
    const shipping_group_name       = $(this).closest('tr').find('.shipping_group_name').text().trim();
    const completed_orders_count    = $(this).closest('tr').find('.completed_orders_count').text().trim();
    try {
        const result = window.confirm(
            "以下の出荷完了を実行しますか？\n\n" +
            "出荷予定日：" + estimated_shipping_date +
            "\n出荷倉庫：" + shipping_base_name +
            "\n出荷グループ名：" + shipping_group_name +
            "\n出荷完了対象件数：" + completed_orders_count
        );
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true) {
            start_loading();
            $("#shipping_group_id").val($(this).data('shipping-group-id'));
            $("#shipping_work_end_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

// 出荷完了対象件数のツールチップ
tippy('.tippy_shipping_work_end_target_count', {
    content: "出荷作業中かつ出荷検品が完了している<br>注文件数が表示されます",
    duration: 500,
    maxWidth: 'none',
    allowHTML: true,
    placement: 'right',
    theme: 'tippy_main_theme',
});

// 出荷完了対象外件数のツールチップ
tippy('.tippy_not_shipping_work_end_target_count', {
    content: "出荷作業中で出荷検品が未完了の<br>注文件数が表示されます",
    duration: 500,
    maxWidth: 'none',
    allowHTML: true,
    placement: 'right',
    theme: 'tippy_main_theme',
});