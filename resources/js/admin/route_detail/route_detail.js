import start_loading from '../../loading';

// ルート詳細番号を振り直し、削除ボタンの表示・非表示を制御
function updateConditionLabels() {
    // route_detail_divの数を取得
    const total = $('.route_detail_div').length;
    // route_detail_divの分だけループ処理
    $('.route_detail_div').each(function (index) {
        // ルート詳細1、ルート詳細2... と番号を更新
        $(this).find('p.text-base').text('ルート詳細 ' + (index + 1));
        // 削除ボタンがなければ追加
        let $deleteBtn = $(this).find('.delete_route_detail_btn');
        if($deleteBtn.length === 0){
            $(this).find('div.flex.justify-between').append(
                '<button type="button" class="delete_route_detail_btn btn bg-red-500 text-white px-2 py-1 rounded text-xs">削除</button>'
            );
            $deleteBtn = $(this).find('.delete_route_detail_btn');
        }
        // ルート詳細が1つだけなら削除ボタン非表示、それ以外は表示
        if(total === 1){
            $deleteBtn.hide();
        }else{
            $deleteBtn.show();
        }
    });
}

// ルート詳細追加処理
$('#add_route_detail_btn').on('click', function () {
    // 最後にあるroute_detail_divを取得して複製
    const $last = $('.route_detail_div').last();
    const $clone = $last.clone();
    // 入力値/クラスを初期化して追加
    $clone.find('select, input').val('');
    $clone.find('.bg-gray-300').removeClass('bg-gray-300');
    $clone.find(':disabled').prop('disabled', false);
    $('#route_detail_wrapper').append($clone);
    updateConditionLabels();
});

// ルート詳細削除処理
$('#route_detail_wrapper').on('click', '.delete_route_detail_btn', function () {
    $(this).closest('.route_detail_div').remove();
    updateConditionLabels();
});

// 初期化（リロード対策）
updateConditionLabels();

// 設定ボタンを押下した場合
$('#route_detail_update_enter').on("click",function(){
    // AJAX通信のURLを定義
    const ajax_url = '/route_detail_update/ajax_validation';
    // バリデーションで使用するデータを整理
    const data = collectValidationData();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: ajax_url,
        type: 'GET',
        data: data,
        dataType: 'json',
        success: function(data){
            // 処理を実行するか確認
            const result = window.confirm("ルート詳細の更新を実行しますか？");
            // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
            if(result === true){
                start_loading();
                $("#route_detail_update_form").submit();
            }
        },
        error: function(xhr){
            if(xhr.status === 422){
                // バリデーションエラーを取得
                const errors = xhr.responseJSON.errors;
                // エラー情報を格納する変数を宣言
                let validation_errors = '';
                // ここで画面にエラーメッセージ表示など処理
                $.each(errors, function(index, value) {
                    // index が "stop_order.0" のような形式であることを想定
                    // .数字の部分を正規表現で抽出
                    const match = index.match(/\.(\d+)$/);
                    // 数字に+1する（これがルート詳細XのXの部分となる）
                    const number = parseInt(match[1], 10) + 1;
                    // 変数にエラー情報を格納
                    validation_errors += `ルート詳細 ${number}: ${value[0]}\n`;
                });
                alert(validation_errors);
            }else{
                alert('通信エラーが発生しました。');
            }
        }
    });
});

// 出発時刻と到着時刻を変更した場合
$(document).on('change', 'input[name="departure_time[]"], input[name="arrival_time[]"]', function () {
    // valueを更新する
    $(this).attr('value', $(this).val());
});

// バリデーションで使用するデータを整理
function collectValidationData()
{
    return {
        route_id: $('#route_id').val(),
        boarding_location_id: $("select[name='boarding_location_id[]']").map(function () {
            return $(this).val();
        }).get(),
        stop_order: $("input[name='stop_order[]']").map(function () {
            return $(this).val();
        }).get(),
        arrival_time: $("input[name='arrival_time[]']").map(function () {
            return $(this).val();
        }).get(),
        departure_time: $("input[name='departure_time[]']").map(function () {
            return $(this).val();
        }).get(),
    };
}