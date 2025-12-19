import Chart from "chart.js/auto";
import colorMap from '../../color';
import start_loading from '../../loading';

// グラフの存在確認用変数
let chart = null;

// 画面読み込み時の処理
$(document).ready(function() {
    // グラフ要素が存在する場合
    if($('#abc_analysis_chart').length){
        // オブジェクトを作成
        create_object();
    }
});

// オブジェクトを作成
function create_object(){
    // AJAX通信のURLを定義
    const ajax_url = '/abc_analysis/ajax_get_chart_data';
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: ajax_url,
        type: 'GET',
        data: {},
        dataType: 'json',
        success: function(data){
            try {
                // グラフを作成
                create_chart(data);
            } catch (e) {
                alert('オブジェクトの生成に失敗しました。');
            }
        },
        error: function(){
            alert('オブジェクトの生成に失敗しました。');
        }
    });
};

// グラフを作成
function create_chart(data){
    // すでにグラフが存在する場合
    if(chart){
        // 削除
        chart.destroy();
    }
    // ラベルを格納する配列を初期化
    let labels = [];
    // 商品の分だけループ処理
    $.each(data['result'], function(index, value) {
        // 商品を配列に格納
        labels.push(value['item_name']);
    });
    // 表示する情報や設定を配列に格納
    const chart_data = {
        labels: labels,
        datasets: [
            ...getShippingQuantityDataset(data['result']),
            getCumulativeRatioDataset(data['result'])
        ]
    };
    // HTML内にある <canvas id="shipping_count_chart"> 要素を取得し、その2D描画コンテキストを取得する
    // Chart.js はこのコンテキストを使ってグラフを描画する
    const canvas = document.getElementById("abc_analysis_chart");
    const ctx = canvas.getContext("2d");
    // 横軸の項目数に応じて幅を広げる
    canvas.width = Math.max(1200, data['result'].length * 40); // 1項目あたりのpx
    canvas.height = 600;
    // Chart.js を使って新しい折れ線グラフ(Line Chart)を作成する
    chart = new Chart(ctx, {
        // グラフに表示するデータ
        data: chart_data,
        // オプション設定
        options: {
            responsive: false,
            scales: {
                "y-axis-quantity": {
                    type: "linear",
                    position: "left",
                    ticks: {
                        max: 500,
                        min: 0,
                        stepSize: 50,
                        precision: 0,
                        callback: function(value) {
                            return Number.isInteger(value) ? value : null;
                        }
                    },
                    grid: {
                        drawOnChartArea: false // 左右のグリッドが重ならないように
                    }
                },
                "y-axis-ratio": {
                    type: "linear",
                    position: "right",
                    ticks: {
                        max: 100,
                        min: 0,
                        stepSize: 10,
                        precision: 0,
                        callback: function(value) {
                            return Number.isInteger(value) ? value : null;
                        }
                    }
                },
                x: {
                    ticks: {
                        autoSkip: false,       // すべて表示
                        
                    },
                }
            },
            plugins: {
                legend: {
                    labels: {
                        // 凡例がpointStyleに従う
                        usePointStyle: true
                    }
                },
                tooltip: {
                    bodyFont: {
                        size: 16,       // ツールチップ本文のフォントサイズ
                    },
                    titleFont: {
                        size: 18,       // ツールチップタイトルのフォントサイズ
                    },
                    padding: 12,        // 内側の余白
                    boxPadding: 6,      // カラーボックスの余白
                },
            }
        }
    });
}

function getShippingQuantityDataset(result) {
    // データ配列を初期化
    let dataA = [];
    let dataB = [];
    let dataC = [];

    $.each(result, function(index, value) {
        switch(value['rank']) {
            case 'A':
                dataA.push(value['total_ship_quantity']);
                dataB.push(null);
                dataC.push(null);
                break;
            case 'B':
                dataA.push(null);
                dataB.push(value['total_ship_quantity']);
                dataC.push(null);
                break;
            case 'C':
                dataA.push(null);
                dataB.push(null);
                dataC.push(value['total_ship_quantity']);
                break;
        }
    });

    return [
        {
            type: 'bar',
            label: 'Aランク',
            data: dataA,
            backgroundColor: colorMap[1].backgroundColor,
            borderColor: colorMap[1].borderColor,
            yAxisID: "y-axis-quantity"
        },
        {
            type: 'bar',
            label: 'Bランク',
            data: dataB,
            backgroundColor: colorMap[2].backgroundColor,
            borderColor: colorMap[2].borderColor,
            yAxisID: "y-axis-quantity"
        },
        {
            type: 'bar',
            label: 'Cランク',
            data: dataC,
            backgroundColor: colorMap[3].backgroundColor,
            borderColor: colorMap[3].borderColor,
            yAxisID: "y-axis-quantity"
        }
    ];
}

// 累積構成比のデータを取得
function getCumulativeRatioDataset(result)
{
    // 累積構成比を格納する配列を初期化
    let cumulative_ratio_arr = [];
    // 商品の分だけループ処理
    $.each(result, function(index, value) {
        // 累積構成比を変数に格納
        let cumulative_ratio = value['cumulative_ratio'];
        // 累積構成比を配列に格納
        cumulative_ratio_arr.push(cumulative_ratio);
    });
    return {
        type: 'line',
        label: '累積構成比',
        data: cumulative_ratio_arr,
        borderColor: colorMap[0].borderColor,
        backgroundColor: colorMap[0].backgroundColor,
        pointBackgroundColor: colorMap[0].borderColor,
        pointRadius: 5,
        pointHoverRadius: 7,
        yAxisID: "y-axis-ratio"
    };
}

// 表示切替ボタンを押下した場合
$('.display_switch').on("click",function(){
    start_loading();
});

// リスト表示のツールチップ
tippy('.tippy_disp_type_list', {
    content: "リスト表示",
    duration: 500,
    maxWidth: 'none',
    allowHTML: true,
    placement: 'right',
    theme: 'tippy_main_theme',
});

// グラフ表示のツールチップ
tippy('.tippy_disp_type_chart', {
    content: "グラフ表示",
    duration: 500,
    maxWidth: 'none',
    allowHTML: true,
    placement: 'right',
    theme: 'tippy_main_theme',
});