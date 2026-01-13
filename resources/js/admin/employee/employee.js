import start_loading from '../../loading';

// 追加ボタンを押下した場合
$('#employee_create_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("従業員追加を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#employee_form").submit();
    }
});

// 更新ボタンを押下した場合
$('#employee_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("従業員更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#employee_form").submit();
    }
});

// アップロードでファイルが選択されたら
$('.select_file input[type=file]').on("change",function(){
    // 処理を実行するか確認
    const result = window.confirm("従業員追加(取込)を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true){
        start_loading();
        $("#employee_create_import_form").submit();
    }
    // 要素をクリア
    $('.select_file input[type=file]').val(null);
});