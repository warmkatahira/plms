import start_loading from '../../loading';

// 追加ボタンを押下した場合
$('#ride_schedule_create_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("送迎予定追加を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#ride_schedule_form").submit();
    }
});

// 更新ボタンを押下した場合
$('#ride_schedule_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("送迎予定更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#ride_schedule_form").submit();
    }
});

// 削除ボタンが押下されたら
$('.ride_schedule_delete_enter').on("click",function(){
    // 削除ボタンが押下された要素の親のtrタグを取得
    const tr = $(this).closest('tr');
    // 削除しようとしている要素のルート名を取得
    const route_name = tr.find('.route_name').text();
    try {
        // 処理を実行するか確認
        const result = window.confirm("送迎予定を削除しますか？\n\n" + 'ルート名：' + route_name);
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true){
            start_loading();
            // 削除対象の送迎予定IDを要素にセット
            $('#ride_id_at_delete').val($(this).data('route-id'));
            $("#ride_schedule_delete_form").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

document.addEventListener("DOMContentLoaded", function () {
    flatpickr("#schedule_date", {
        mode: "multiple",
        dateFormat: "Y-m-d",
        locale: "ja",
        showMonths: 1
    });
});

// 利用者のツールチップ
tippy('.tippy_ride_user', {
    content(reference) {
        const fullName = reference.getAttribute('data-full-name') + ' さん' || '';
        return fullName;
    },
    duration: 500,
    maxWidth: 'none',
    allowHTML: true,
    placement: 'right',
    theme: 'tippy_main_theme',
});