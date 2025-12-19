import start_loading from '../../loading';

// 表示切替ボタンを押下した場合
$('.display_switch').on("click",function(){
    start_loading();
});

// 単品商品のツールチップ
tippy('.tippy_display_by_item', {
    content: "単品商品",
    duration: 500,
    maxWidth: 'none',
    allowHTML: true,
    placement: 'right',
    theme: 'tippy_main_theme',
});

// セット商品のツールチップ
tippy('.tippy_display_by_set_item', {
    content: "セット商品",
    duration: 500,
    maxWidth: 'none',
    allowHTML: true,
    placement: 'right',
    theme: 'tippy_main_theme',
});