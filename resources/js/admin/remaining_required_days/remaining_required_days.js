import start_loading from '../../loading';

// 更新ボタンを押下した場合
$('#remaining_required_days_enter').on("click",function(){
    // 処理を実行するか確認
    const result = window.confirm("義務残通知を実行しますか？");
    // 「はい」が押下されたらsubmit、「いいえ」が押下されたら処理キャンセル
    if(result === true){
        start_loading();
        $("#remaining_required_days_form").submit();
    }
});

// 1段目：「営業所内訳を見る」ボタンで営業所リストを開閉
document.querySelectorAll('.toggle-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const detail = btn.previousElementSibling;
        const isHidden = detail.style.display === 'none';
        detail.style.display = isHidden ? 'flex' : 'none';
        btn.querySelector('.arrow').textContent = isHidden ? '▾' : '▸';
        btn.querySelector('.label').textContent = isHidden ? '閉じる' : '営業所内訳を見る';
    });
});

// 2段目：各営業所行で氏名リストを開閉
document.querySelectorAll('.base-toggle-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const list = btn.nextElementSibling;
        const isHidden = list.style.display === 'none' || list.style.display === '';
        list.style.display = isHidden ? 'flex' : 'none';
        btn.querySelector('.arrow').textContent = isHidden ? '▾' : '▸';
    });
});