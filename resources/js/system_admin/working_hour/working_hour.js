import start_loading from '../../loading';

// 削除ボタンを押下した場合
$('.working_hour_delete_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("削除を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        // 削除対象の商品IDを要素にセット
        $('#working_hour_id').val($(this).data('working-hour-id'));
        $("#working_hour_delete_form").submit();
    }
});