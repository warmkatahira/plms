import{s as e}from"./loading-CLXJ3Lsj.js";$("#route_create_enter").on("click",function(){window.confirm("ルート追加を実行しますか？")===!0&&(e(),$("#route_form").submit())});$("#route_update_enter").on("click",function(){window.confirm("ルート更新を実行しますか？")===!0&&(e(),$("#route_form").submit())});$(".route_delete_enter").on("click",function(){const o=$(this).closest("tr").find(".route_name").text();try{window.confirm(`ルートを削除しますか？

ルート名：`+o)==!0&&(e(),$("#route_id_at_delete").val($(this).data("route-id")),$("#route_delete_form").submit())}catch(t){alert(t.message)}});$(".route_copy_enter").on("click",function(){const o=$(this).closest("tr").find(".route_name").text();try{window.confirm(`ルートを複製しますか？

ルート名：`+o)==!0&&(e(),$("#route_id_at_copy").val($(this).data("route-id")),$("#route_copy_form").submit())}catch(t){alert(t.message)}});
