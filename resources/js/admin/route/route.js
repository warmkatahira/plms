import start_loading from '../../loading';

// 追加ボタンを押下した場合
$('#route_create_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("ルート追加を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#route_form").submit();
    }
});

// 更新ボタンを押下した場合
$('#route_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("ルート更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#route_form").submit();
    }
});

// 削除ボタンが押下されたら
$('.route_delete_enter').on("click",function(){
    // 削除ボタンが押下された要素の親のtrタグを取得
    const tr = $(this).closest('tr');
    // 削除しようとしている要素のルート名を取得
    const route_name = tr.find('.route_name').text();
    try {
        // 処理を実行するか確認
        const result = window.confirm("ルートを削除しますか？\n\n" + 'ルート名：' + route_name);
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true){
            start_loading();
            // 削除対象のルートIDを要素にセット
            $('#route_id_at_delete').val($(this).data('route-id'));
            $("#route_delete_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

// 複製ボタンが押下されたら
$('.route_copy_enter').on("click",function(){
    // 複製ボタンが押下された要素の親のtrタグを取得
    const tr = $(this).closest('tr');
    // 複製しようとしている要素のルート名を取得
    const route_name = tr.find('.route_name').text();
    try {
        // 処理を実行するか確認
        const result = window.confirm("ルートを複製しますか？\n\n" + 'ルート名：' + route_name);
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true){
            start_loading();
            // 複製対象のルートIDを要素にセット
            $('#route_id_at_copy').val($(this).data('route-id'));
            $("#route_copy_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

// ルート更新のツールチップ
tippy('.tippy_route_update', {
    content: 'ルート更新',
    duration: 500,
    maxWidth: 'none',
    allowHTML: true,
    placement: 'right',
    theme: 'tippy_main_theme',
});

// ルート詳細更新のツールチップ
tippy('.tippy_route_detail_update', {
    content: 'ルート詳細更新',
    duration: 500,
    maxWidth: 'none',
    allowHTML: true,
    placement: 'right',
    theme: 'tippy_main_theme',
});

// 複製のツールチップ
tippy('.tippy_route_copy', {
    content: '複製',
    duration: 500,
    maxWidth: 'none',
    allowHTML: true,
    placement: 'right',
    theme: 'tippy_main_theme',
});

// 削除のツールチップ
tippy('.tippy_route_delete', {
    content: '削除',
    duration: 500,
    maxWidth: 'none',
    allowHTML: true,
    placement: 'right',
    theme: 'tippy_main_theme',
});

// ルート詳細表示/非表示のツールチップ
tippy('.tippy_route_detail_open_close', {
    content: 'ルート詳細表示/非表示',
    duration: 500,
    maxWidth: 'none',
    allowHTML: true,
    placement: 'right',
    theme: 'tippy_main_theme',
});