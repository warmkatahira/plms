import start_loading from '../../loading';

// 追加ボタンが押下されたら
$('#shipper_create_enter').on("click",function(){
    try {
        // 処理を実行するか確認
        const result = window.confirm("荷送人を追加しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true) {
            start_loading();
            $("#shipper_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

// 更新ボタンが押下されたら
$('#shipper_update_enter').on("click",function(){
    try {
        // 処理を実行するか確認
        const result = window.confirm("荷送人を更新しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true) {
            start_loading();
            $("#shipper_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});