// +-+-+-+-+-+-+-+-+-+-+-+-+- メニュー用 +-+-+-+-+-+-+-+-+-+-+-+-+-

// マウスオーバー時（表示）
$(document).on("mouseover", ".dropdown-menu", function(){
    $(this).find(".dropdown-menu-content").show();
});

// マウスアウト時（非表示）
$(document).on("mouseout", ".dropdown-menu", function(){
    $(this).find(".dropdown-menu-content").hide();
});

// クリック時（非表示）
$(document).on("click", ".dropdown-menu", function(){
    $(this).find(".dropdown-menu-content").hide();
});

// +-+-+-+-+-+-+-+-+-+-+-+-+- 操作用 +-+-+-+-+-+-+-+-+-+-+-+-+-

// マウスオーバー時（表示）
$(document).on("mouseover", ".dropdown-operation", function(){
    $(this).find(".dropdown-operation-content").show();
});

// マウスアウト時（非表示）
$(document).on("mouseout", ".dropdown-operation", function(){
    $(this).find(".dropdown-operation-content").hide();
});

// クリック時（非表示）
$(document).on("click", ".dropdown-operation", function(){
    $(this).find(".dropdown-operation-content").hide();
});