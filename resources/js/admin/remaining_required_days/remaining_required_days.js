import start_loading from '../../loading';

// 更新ボタンを押下した場合
$('#remaining_required_days_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("義務残通知を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#remaining_required_days_form").submit();
    }
});