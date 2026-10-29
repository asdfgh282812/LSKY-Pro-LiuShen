@section('title', '角色組管理')

<x-app-layout>
    <div class="my-6 md:my-9">
        <div class="mb-5 flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, rgba(16,185,129,0.15), rgba(13,148,136,0.15));">
                <i class="fas fa-layer-group text-sm" style="color: #059669;"></i>
            </div>
            <h2 class="font-bold text-lg text-slate-800">角色組管理</h2>
        </div>
        <form action="{{ route('admin.groups') }}" method="get">
            <div class="mb-3 flex justify-between w-full">
                <x-button type="button" onclick="window.location.href = '{{ route('admin.group.create') }}'">建立角色組</x-button>
                <input class="px-3 py-1.5 text-sm rounded-lg bg-white border border-slate-200 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400" name="keywords" placeholder="輸入名稱回車搜尋..." value="{{ request('keywords') }}" />
            </div>
        </form>

        <x-table :columns="['ID', '名稱', '是否預設', '是否為訪客組', '圖片審核', '原圖保護', '浮水印' ,'使用者數量', '策略數量', '操作']">
            @foreach($groups as $group)
            <tr data-id="{{ $group->id }}">
                <td class="px-6 py-4 whitespace-nowrap">{{ $group->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap name">{{ $group->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $group->is_default ? 'text-green-500' : 'text-rose-500' }}">
                        <i class="text-lg fas fa-{{ $group->is_default ? 'check-circle' : 'times-circle' }}"></i>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $group->is_guest ? 'text-green-500' : 'text-rose-500' }}">
                        <i class="text-lg fas fa-{{ $group->is_guest ? 'check-circle' : 'times-circle' }}"></i>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $group->configs->get('is_enable_scan') ? 'text-green-500' : 'text-rose-500' }}">
                        <i class="text-lg fas fa-{{ $group->configs->get('is_enable_scan') ? 'check-circle' : 'times-circle' }}"></i>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $group->configs->get('is_enable_original_protection') ? 'text-green-500' : 'text-rose-500' }}">
                        <i class="text-lg fas fa-{{ $group->configs->get('is_enable_original_protection') ? 'check-circle' : 'times-circle' }}"></i>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $group->configs->get('is_enable_watermark') ? 'text-green-500' : 'text-rose-500' }}">
                        <i class="text-lg fas fa-{{ $group->configs->get('is_enable_watermark') ? 'check-circle' : 'times-circle' }}"></i>
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $group->users_count }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $group->strategies_count }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <a href="{{ route('admin.group.edit', ['id' => $group->id]) }}" class="text-emerald-600 hover:text-emerald-900">編輯</a>
                    @if(! $group->is_default && ! $group->is_guest)
                    <a href="javascript:void(0)" data-operate="delete" class="text-red-600 hover:text-red-900">刪除</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </x-table>
        @if($groups->isEmpty())
            <x-no-data message="沒有找到任何角色組"/>
        @else
            <div class="mt-4">
                {{ $groups->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            $('[data-operate="delete"]').click(function () {
                Swal.fire({
                    title: `確認刪除角色組【${$(this).closest('tr').find('td.name').text()}】嗎?`,
                    text: "⚠️注意，刪除該角色組後，該角色組下屬的使用者會被重置為系統預設組。",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '確認刪除',
                }).then((result) => {
                    if (result.isConfirmed) {
                        let id = $(this).closest('tr').data('id');
                        axios.delete(`/admin/groups/${id}`).then(response => {
                            if (response.data.status) {
                                history.go(0);
                            } else {
                                toastr.error(response.data.message);
                            }
                        });
                    }
                })
            });
        </script>
    @endpush

</x-app-layout>
