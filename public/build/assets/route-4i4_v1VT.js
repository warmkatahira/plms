import{s as t}from"./loading-CLXJ3Lsj.js";$("#route_create_enter").on("click",function(){window.confirm("ルート追加を実行しますか？")===!0&&(t(),$("#route_form").submit())});$("#route_update_enter").on("click",function(){window.confirm("ルート更新を実行しますか？")===!0&&(t(),$("#route_form").submit())});$(".route_delete_enter").on("click",function(){const o=$(this).closest("tr").find(".route_name").text();try{window.confirm(`ルートを削除しますか？

ルート名：`+o)==!0&&(t(),$("#route_id").val($(this).data("route-id")),$("#route_delete_form").submit())}catch(r){alert(r.message)}});
