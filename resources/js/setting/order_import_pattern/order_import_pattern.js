import start_loading from '../../loading';

// 追加ボタンが押下されたら
$('#order_import_pattern_create_enter').on("click",function(){
    try {
        // 処理を実行するか確認
        const result = window.confirm("受注取込パターンを追加しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true){
            start_loading();
            $("#order_import_pattern_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

// 追加ボタンが押下されたら
$('#order_import_pattern_update_enter').on("click",function(){
    try {
        // 処理を実行するか確認
        const result = window.confirm("受注取込パターンを更新しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true){
            start_loading();
            $("#order_import_pattern_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

// 削除ボタンが押下されたら
$('.order_import_pattern_delete_enter').on("click",function(){
    // 削除ボタンが押下された要素の親のtrタグを取得
    const tr = $(this).closest('tr');
    // 削除しようとしている要素のパターン名を取得
    const pattern_name = tr.find('.pattern_name').text();
    try {
        // 処理を実行するか確認
        const result = window.confirm("受注取込パターンを削除しますか？\n\n" + 'パターン名：' + pattern_name);
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true){
            start_loading();
            // 削除対象の受注取込パターンIDを要素にセット
            $('#order_import_pattern_id').val($(this).data('order-import-pattern-id'));
            $("#order_import_pattern_delete_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

// カラム取得方法のツールチップ
tippy('.tippy_column_get_method', {
    content: "「名称」・・・紐付けをヘッダーの名称で行います。<br>" + 
             "カラム設定では、ヘッダーの名称を入力して下さい。<br>" +
             "例）注文番号、商品コード、など<br><br>" +
             "「位置」・・・紐付けを列位置で行います。<br>" +
             "カラム設定では、列位置を入力して下さい。<br>" +
             "例）1、12、など",
    duration: 500,
    maxWidth: 'none',
    allowHTML: true,
    placement: 'right',
    theme: 'tippy_main_theme',
});