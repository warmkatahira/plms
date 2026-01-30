import start_loading from '../../loading';

// 追加ボタンを押下した場合
$('#vehicle_create_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("車両追加を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#vehicle_form").submit();
    }
});

// 更新ボタンを押下した場合
$('#vehicle_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("車両更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#vehicle_form").submit();
    }
});

$(function () {
    function toggleOwner() {
        // 車両区分を取得
        const vehicleTypeId = $('#vehicle_type_id').val();
        // 所有者要素を取得
        const $owner = $('#owner');
        // 所有者要素の親要素を取得
        const $wrapper = $owner.closest('div');
        // 車両区分が1(=社用車)の場合は、所有者要素を非表示、それ以外は表示
        if (vehicleTypeId === '1') {
            // 非表示
            $wrapper.addClass('hidden');
            // 送信しない
            $owner.prop('disabled', true).val('');
        } else {
            // 表示
            $wrapper.removeClass('hidden');
            // 送信する
            $owner.prop('disabled', false);
        }
    }
    // 初期表示時（編集画面対策）
    toggleOwner();
    // 変更時
    $('#vehicle_type_id').on('change', function () {
        toggleOwner();
    });
});