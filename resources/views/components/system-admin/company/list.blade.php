<div class="disable_scrollbar flex flex-grow overflow-scroll my-3">
    <div class="company_list bg-white overflow-x-auto overflow-y-auto border border-gray-600">
        <table class="text-xs">
            <thead>
                <tr class="text-left text-white bg-black whitespace-nowrap sticky top-0">
                    <th class="font-thin py-1 px-2 text-center">操作</th>
                    <th class="font-thin py-1 px-2 text-center">会社ID</th>
                    <th class="font-thin py-1 px-2 text-center">会社名</th>
                    <th class="font-thin py-1 px-2 text-center">並び順</th>
                    <th class="font-thin py-1 px-2 text-center">ユーザー数</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach($companies as $company)
                    <tr class="text-left cursor-default whitespace-nowrap hover:bg-theme-sub group">
                        <td class="py-1 px-2 border">
                            <div class="flex flex-row gap-5">
                                <a href="{{ route('company_update.index', ['company_id' => $company->company_id]) }}" class="btn bg-btn-enter text-white py-1 px-2">更新</a>
                            </div>
                        </td>
                        <td class="py-1 px-2 border">{{ $company->company_id }}</td>
                        <td class="py-1 px-2 border">{{ $company->company_name }}</td>
                        <td class="py-1 px-2 border text-right">{{ number_format($company->sort_order) }}</td>
                        <td class="py-1 px-2 border text-right">{{ $company->users->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>