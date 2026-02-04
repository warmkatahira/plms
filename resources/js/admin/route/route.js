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
            $('#route_id').val($(this).data('route-id'));
            $("#route_delete_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});