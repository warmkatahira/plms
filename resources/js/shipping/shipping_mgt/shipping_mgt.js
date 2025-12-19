import start_loading from '../../loading';
import get_checkbox from '../../checkbox';

// 出荷グループのプルダウンを変更したら
$('#search_shipping_group_id').on("change",function(){
    start_loading();
    $("#shipping_group_select_form").submit();
});

// クリックイベント
$(document).on('click', function(e){
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('shipping_group_update_modal_close') === true){
        $('#shipping_group_update_modal').addClass('hidden');
    }
    // クリックした要素のIDがモーダルを開くものであれば、モーダルを開く
    if(e.target.id === 'shipping_group_update_modal_open'){
        // テキストボックスを現在の値に変更
        $('#shipping_group_name').val($('#current_shipping_group_name').val());
        $('#estimated_shipping_date').val($('#current_estimated_shipping_date').val());
        $('#shipping_group_update_modal').removeClass('hidden');
    }
    // クリックされた要素にモーダルを閉じるクラス名が設定されていれば、モーダルを閉じる
    if(e.target.classList.contains('select_order_document_modal_close') === true){
        $('#select_order_document_modal').addClass('hidden');
    }
    // クリックした要素のIDがモーダルを開くものであれば、モーダルを開く
    if(e.target.id === 'select_order_document_modal_open'){
        $('#select_order_document_modal').removeClass('hidden');
    }
});

// 出荷グループの更新ボタンを押下した場合
$('#shipping_group_update_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("出荷グループの更新を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#shipping_group_update_form").submit();
    }
});

// 出荷待ちへ戻すを押下したら
$('#return_to_shukka_machi').on("click",function(){
    try {
        // チェックボックス要素関連の情報を取得
        const [chk, count, all] = get_checkbox();
        // 対象が1つ以上選択されているか
        if(count == 0){
            throw new Error('対象が選択されていません。');
        }
        // 確認のためのインプットボックスを表示
        const input = prompt(count + "件の受注を出荷待ちへ戻しますか？\n続行するには「back」と入力してください。");
        // インプットボックスに「back」と入力された場合のみ処理を実行
        if (input === 'back') {
            // formタグのactionを変更
            $('#operation_div_form').attr('action', '/return_to_shukka_machi/enter');
            // formタグからtarget="_blank"を外す
            $('#operation_div_form').removeAttr('target');
            $("#operation_div_form").submit();
        } else {
            alert("出荷待ちへ戻すはキャンセルされました。");
        }
    } catch (e) {
        alert(e.message);
    }
});

// 帳票作成(選択注文のみ)を押下したら
$('.select_order_document').on("click",function(){
    // 帳票名を取得
    const document_name = $(this).html();
    // ボタンのID（帳票種類）を取得
    const document_type = $(this).attr('id');
    try {
        // チェックボックス要素関連の情報を取得
        const [chk, count, all] = get_checkbox();
        // 対象が1つ以上選択されているか
        if(count == 0){
            throw new Error('対象が選択されていません。');
        }
        // 処理を実行するか確認
        const result = window.confirm(count + "件の" + document_name + "作成を実行しますか？");
        // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
        if(result == true) {
            // formタグのactionを変更
            // 個別ピッキングリスト
            if(document_type === 'kobetsu_picking_list'){
                $('#operation_div_form').attr('action', '/kobetsu_picking_list_create/create_by_select_order');
            }
            // 納品書
            if(document_type === 'delivery_note'){
                $('#operation_div_form').attr('action', '/delivery_note_create/create_by_select_order');
            }
            // 荷札データ
            if(document_type === 'nifuda'){
                $('#operation_div_form').attr('action', '/nifuda_create/create_by_select_order');
            }
            // formタグにtarget="_blank"をつける
            $('#operation_div_form').attr('target', '_blank');
            $("#operation_div_form").submit();
        } else {
            alert(document_name + "作成はキャンセルされました。");
        }
    } catch (e) {
        alert(e.message);
    }
});

// 出荷管理の受注リストの高さ調整
function adjustmentShippingListHeight() {
    // 要素を取得
    const $header = $('#shipping_group_select_div');
    const $list = $('.shipping_mgt_list');
    // 高さを取得
    const headerHeight = $header.outerHeight();
    // その他の固定の余白やパディングなどのオフセット
    const offset = 140;
    // ウィンドウの高さからヘッダーの高さとオフセットを差し引いた値をリストの高さに設定
    $list.css('height', `calc(100vh - ${headerHeight + offset}px)`);
}

// ウィンドウの読み込み完了時に高さを調整する処理を実行
$(window).on('load', adjustmentShippingListHeight);

// アップロードでファイルが選択されたら
$('.select_file input[type=file]').on("change",function(){
    // 処理を実行するか確認
    const result = window.confirm("配送伝票番号取込を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result == true){
        start_loading();
        $("#tracking_no_import_form").submit();
    }
    // 要素をクリア
    $('.select_file input[type=file]').val(null);
});