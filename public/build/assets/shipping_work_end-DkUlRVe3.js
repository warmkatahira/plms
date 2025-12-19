import{s}from"./loading-CLXJ3Lsj.js";$(".shipping_work_end_enter").on("click",function(){const i=$(this).closest("tr").find(".estimated_shipping_date").text().trim(),n=$(this).closest("tr").find(".shipping_base_name").text().trim(),e=$(this).closest("tr").find(".shipping_group_name").text().trim(),p=$(this).closest("tr").find(".completed_orders_count").text().trim();try{window.confirm(`以下の出荷完了を実行しますか？

出荷予定日：`+i+`
出荷倉庫：`+n+`
出荷グループ名：`+e+`
出荷完了対象件数：`+p)==!0&&(s(),$("#shipping_group_id").val($(this).data("shipping-group-id")),$("#shipping_work_end_form").submit())}catch(t){alert(t.message)}});tippy(".tippy_shipping_work_end_target_count",{content:"出荷作業中かつ出荷検品が完了している<br>注文件数が表示されます",duration:500,maxWidth:"none",allowHTML:!0,placement:"right",theme:"tippy_main_theme"});tippy(".tippy_not_shipping_work_end_target_count",{content:"出荷作業中で出荷検品が未完了の<br>注文件数が表示されます",duration:500,maxWidth:"none",allowHTML:!0,placement:"right",theme:"tippy_main_theme"});
