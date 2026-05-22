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
                            {{-- プログレスバー --}}
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $barColor }} rounded-full" style="width: {{ $ratio }}%"></div>
                                </div>
                                <span class="text-xs text-gray-400 w-8">{{ $ratio }}%</span>
                            </div>

                            {{-- 1段目：営業所内訳アコーディオン（初期非表示） --}}
                            <div class="base-detail mt-2 flex-col gap-1" style="display:none;">
                                @foreach($data['bases'] as $base)
                                    <div>
                                        {{-- 2段目：営業所行（クリックで氏名リストトグル） --}}
                                        <button
                                            type="button"
                                            class="base-toggle-btn w-full flex justify-between items-center text-xs bg-theme-sub hover:bg-amber-50 rounded px-2 py-1 transition-colors text-left"
                                        >
                                            <span class="flex items-center gap-1">
                                                <span class="arrow text-gray-400">▸</span>
                                                <span class="font-medium text-gray-700">{{ $base['base_name'] }}</span>
                                            </span>
                                            <span class="text-gray-500">{{ $base['count'] }}人</span>
                                        </button>

                                        {{-- 氏名リスト（初期非表示） --}}
                                        <div class="employee-list flex-col gap-0.5 mt-1 ml-3" style="display:none;">
                                            @foreach($base['employees'] as $emp)
                                                <div class="flex justify-between items-center text-xs text-gray-600 bg-red-100 border border-gray-100 rounded px-2 py-1">
                                                    <span>{{ $emp['name'] }}</span>
                                                    <span class="text-gray-400">残 {{ $emp['remaining_required_days'] }}日</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- 1段目トグルボタン --}}
                            <button class="toggle-btn mt-1 text-xs bg-gray-100 hover:bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full transition-colors">
                                <span class="arrow">▸</span> <span class="label">営業所内訳を見る</span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</x-app-layout>
@vite(['resources/js/admin/remaining_required_days/remaining_required_days.js'])