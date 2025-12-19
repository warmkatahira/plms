import start_loading from '../../loading';

// 追加ボタンを押下した場合
$('#mall_create_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("モール追加を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#mall_form").submit();
    }
});

// 更新ボタンを押下した場合
$('#mall_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("モール更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#mall_form").submit();
    }
});

// 選択したファイル名を表示する
$('#image_file').on("change",function(){
    const file = this.files[0];
    if(file){
        $('#image_file_name').text(file.name);
    }
})