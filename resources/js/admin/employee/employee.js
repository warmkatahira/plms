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
    // フォームを取得
    const form = $(this).closest('form');
    // フォームのIDを取得
    const form_id = form.attr('id');
    // 変数を初期化
    let message = null;
    // フォームIDによってメッセージを可変
    if(form_id === 'employee_create_import_form'){
        message = '従業員追加(取込)を実行しますか？';
    }else if(form_id === 'paid_leave_update_import_form'){
        message = '有給情報更新(取込)を実行しますか？';
    }else if(form_id === 'statutory_leave_update_import_form'){
        message = '義務情報更新(取込)を実行しますか？';
    }
    // 処理を実行するか確認
    const result = window.confirm(message);
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true){
        start_loading();
        form.submit();
    }
    // 要素をクリア
    $(this).val(null);
});