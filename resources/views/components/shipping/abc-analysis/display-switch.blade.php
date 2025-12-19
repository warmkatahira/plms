<div class="flex flex-row ml-auto items-start divide-x divide-black border border-black">
    <!-- リスト表示 -->
    <a href="{{ route('abc_analysis.index', ['disp_type' => 'list']) }}" class="btn display_switch tippy_disp_type_list px-2 py-1 {{ session('disp_type') === 'list' ? 'bg-theme-sub' : 'bg-white' }}">
        <i class="las la-list-ul la-2x"></i>
    </a>
    <!-- グラフ表示 -->
    <a href="{{ route('abc_analysis.index', ['disp_type' => 'chart']) }}" class="btn display_switch tippy_disp_type_chart px-2 py-1 {{ session('disp_type') === 'chart' ? 'bg-theme-sub' : 'bg-white' }}">
        <i class="las la-chart-bar la-2x"></i>
    </a>
</div>