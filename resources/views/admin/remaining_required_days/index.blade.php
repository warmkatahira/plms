<x-app-layout>
    @if($employees->isEmpty())
        <p class="text-base">義務日数残のある従業員はいません。</p>
    @else
        <div class="my-3">
            <form method="post" action="{{ route('remaining_required_days.enter') }}" id="remaining_required_days_form">
                @csrf
                <button type="button" id="remaining_required_days_enter" class="btn bg-btn-enter text-white rounded px-10 py-2"><i class="las la-envelope la-lg mr-1"></i>通知する</button>
            </form>
        </div>
        {{-- サマリーカード --}}
        <div class="grid grid-cols-12 gap-3 mb-6">
            <div class="bg-amber-100 rounded-lg p-4 col-span-2">
                <p class="text-xs text-amber-700 mb-1">対象従業員数</p>
                <p class="text-2xl font-medium text-amber-900">{{ $employees->count() }}<span class="text-sm font-normal text-amber-700">人</span></p>
            </div>
            <div class="bg-amber-100 rounded-lg p-4 col-span-2">
                <p class="text-xs text-amber-700 mb-1">最大義務残日数</p>
                <p class="text-2xl font-medium text-amber-900">{{ $grouped->keys()->first() ?? '-' }}<span class="text-sm font-normal text-amber-700">日</span></p>
            </div>
        </div>
        {{-- 集計テーブル --}}
        <table class="w-full text-sm border border-gray-200 rounded-lg overflow-hidden max-w-2xl">
            <thead class="bg-amber-100 text-xs text-amber-700">
                <tr>
                    <th class="px-4 py-2 text-left font-medium">義務残日数</th>
                    <th class="px-4 py-2 text-right font-medium">人数</th>
                    <th class="px-4 py-2 text-left font-medium">割合 / 営業所内訳</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($grouped as $days => $data)
                    @php
                        $ratio = round($data['count'] / $employees->count() * 100);
                        $barColor = match(true) {
                            $days >= 5 => 'bg-red-400',
                            $days >= 3 => 'bg-amber-400',
                            default    => 'bg-green-400',
                        };
                    @endphp
                    <tr class="border-t border-gray-300">
                        <td class="px-4 py-2 font-medium">{{ $days }}日</td>
                        <td class="px-4 py-2 text-right">{{ $data['count'] }}人</td>
                        <td class="px-4 py-2">
                            {{-- バー --}}
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $barColor }} rounded-full" style="width: {{ $ratio }}%"></div>
                                </div>
                                <span class="text-xs text-gray-400 w-8">{{ $ratio }}%</span>
                            </div>
                            {{-- 営業所内訳（トグル） --}}
                            <div class="base-detail mt-2 flex flex-col gap-1" style="display:none;">
                                @foreach($data['bases'] as $base)
                                    <div class="flex justify-between items-center text-xs text-gray-500 bg-theme-sub rounded px-2 py-1">
                                        <span>{{ $base['base_name'] }}</span>
                                        <span class="font-medium text-gray-700">{{ $base['count'] }}人</span>
                                    </div>
                                @endforeach
                            </div>
                            <button class="toggle-btn mt-1 text-xs bg-gray-100 hover:bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full transition-colors">
                                <span class="arrow">▸</span> <span class="label">営業所内訳</span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <script>
            document.querySelectorAll('.toggle-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const detail = btn.previousElementSibling;
                    const open = detail.classList.toggle('hidden');
                    btn.querySelector('.arrow').textContent = open ? '▸' : '▾';
                    btn.querySelector('.label').textContent = open ? '営業所内訳を見る' : '閉じる';
                });
            });
        </script>
    @endif
</x-app-layout>
@vite(['resources/js/admin/remaining_required_days/remaining_required_days.js'])