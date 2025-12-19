import start_loading from '../../loading';

// 実行ボタンを押下した場合
$('#order_import_api_enter').on("click",function(){
    try {
        // 日時の要素で未入力の数をカウント
        let empty_count = $('.date_time_element').filter(function() {
            return $(this).val().trim() === '';
        }).length;
        // 未入力の要素がある場合
        if(empty_count > 0){
            throw new Error('日時指定が不足しています。');
        }
        // 処理を実行するか確認
        const result = window.confirm("受注取込を実行しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result === true){
            start_loading();
            $("#order_import_form_api").submit();
        }
    } catch (e) {
        alert(e.message);
    }
});

// アップロードでファイルが選択されたら
$('.select_file input[type=file]').on("change",function(){
    // 処理を実行するか確認
    const result = window.confirm("受注取込を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true){
        start_loading();
        $('#' + $(this).attr('id')).submit();
    }
    // 要素をクリア
    $('.select_file input[type=file]').val(null);
});

// クリックイベント
$(document).on('click', function(e){
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('order_import_api_modal_close') === true){
        $('#order_import_api_modal').addClass('hidden');
    }
    // クリックした要素のIDがモーダルを開くものであれば、モーダルを開く
    if(e.target.id === 'order_import_api_modal_open'){
        // モーダルを開く
        $('#order_import_api_modal').removeClass('hidden');
    }
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('order_import_pattern_select_modal_close') === true){
        $('#order_import_pattern_select_modal').addClass('hidden');
    }
    // クリックした要素のIDがモーダルを開くものであれば、モーダルを開く
    if(e.target.id === 'order_import_pattern_select_modal_open'){
        // チェックボックスを全てオフにする
        $('.order_import_pattern_select').prop('checked', false);
        // 全行のハイライトを削除
        $('tbody tr').removeClass('bg-theme-sub');
        // モーダルを開く
        $('#order_import_pattern_select_modal').removeClass('hidden');
    }
});

// 受注取込パターンが選択された場合
$('.order_import_pattern_select').on('change', function() {
    // 選択されているか確認
    const isSelected = document.querySelector('.order_import_pattern_select:checked') !== null;
    // 全行のハイライトを削除
    $('tbody tr').removeClass('bg-theme-sub');
    // 選択されたラジオボタンの親行にハイライトを追加
    if(this.checked){
        $(this).closest('tr').addClass('bg-theme-sub');
    }
    if(isSelected){
        // 選択されている場合、ボタンを表示
        $('#order_import_pattern_file_select_label').show();
    }else{
        // 選択されていない場合、ボタンを非表示
        $('#order_import_pattern_file_select_label').hide();
    }
});

// 入金日のプルダウンが変更されたら
$('#payment_date').on("change",function(){
    // 今日を取得
    const today = new Date();
    const today_format = format(today);
    // 昨日を取得
    const yesterday = new Date(today);
    yesterday.setDate(today.getDate() - 1);
    const yesterday_format = format(yesterday);
    // 今週（日曜開始）
    const currentWeekStart = new Date(today);
    currentWeekStart.setDate(today.getDate() - today.getDay());
    const currentWeekEnd = new Date(currentWeekStart);
    currentWeekEnd.setDate(currentWeekStart.getDate() + 6);
    const current_week_start_format = format(currentWeekStart);
    const current_week_end_format = format(currentWeekEnd);
    // 今月
    const currentMonthStart = new Date(today.getFullYear(), today.getMonth(), 1);
    const currentMonthEnd = new Date(today.getFullYear(), today.getMonth() + 1, 0);
    const current_month_start_format = format(currentMonthStart);
    const current_month_end_format = format(currentMonthEnd);
    // 前月
    const previousMonthStart = new Date(today.getFullYear(), today.getMonth() - 1, 1);
    const previousMonthEnd = new Date(today.getFullYear(), today.getMonth(), 0);
    const previous_month_start_format = format(previousMonthStart);
    const previous_month_end_format = format(previousMonthEnd);
    // カスタム以外の場合
    if ($('#payment_date').val() !== "custom") {
        // 時間をセット
        $('#time_from').val('00:00');
        $('#time_to').val('23:59');
    }
    // 選択された内容に合わせて、要素を変更
    switch($('#payment_date').val()){
        // 今日
		case 'today':
            $('#date_from').val(today_format);
            $('#date_to').val(today_format);
            $('#time_from').val('00:00');
            $('#time_to').val('23:59');
            break;
        // 昨日
		case 'yesterday':
            $('#date_from').val(yesterday_format);
            $('#date_to').val(yesterday_format);
            break;
        // 今週
		case 'current_week':
            $('#date_from').val(current_week_start_format);
            $('#date_to').val(current_week_end_format);
            break;
        // 今月
		case 'current_month':
            $('#date_from').val(current_month_start_format);
            $('#date_to').val(current_month_end_format);
            break;
        // 前月
        case 'previous_month':
            $('#date_from').val(previous_month_start_format);
            $('#date_to').val(previous_month_end_format);
            break;
		default :
            // 何もしない
	}
});

// 入金日の要素が変更されたら
$('.date_time_element').on("change",function(){
    // 入金日のプルダウンをカスタムに変更
    $('#payment_date').val('custom')
});

// 日付をローカル形式でフォーマット
function format(date) {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    return `${y}-${m}-${d}`;
}