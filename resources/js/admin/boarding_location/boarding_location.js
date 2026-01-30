import start_loading from '../../loading';

// 追加ボタンを押下した場合
$('#boarding_location_create_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("乗降場所追加を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#boarding_location_form").submit();
    }
});

// 更新ボタンを押下した場合
$('#boarding_location_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("乗降場所更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#boarding_location_form").submit();
    }
});