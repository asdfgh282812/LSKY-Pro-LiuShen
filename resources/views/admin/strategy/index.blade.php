@section('title', '儲存策略管理')

<x-app-layout>
    <div class="my-6 md:my-9">
        <div class="mb-5 flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, rgba(16,185,129,0.15), rgba(13,148,136,0.15));">
                <i class="fas fa-hdd text-sm" style="color: #059669;"></i>
            </div>
            <h2 class="font-bold text-lg text-slate-800">儲存策略管理</h2>
        </div>
        <form action="{{ route('admin.strategies') }}" method="get">
            <div class="mb-3 flex justify-between w-full">
                <x-button type="button" onclick="window.location.href = '{{ route('admin.strategy.create') }}'">建立儲存策略</x-button>
                <input class="px-3 py-1.5 text-sm rounded-lg bg-white border border-slate-200 text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-400" name="keywords" placeholder="輸入名稱回車搜尋..." value="{{ request('keywords') }}" />
            </div>
        </form>

        <x-table :columns="['ID', '名稱', '驅動', '圖片數量', '已使用儲存', '操作']">
            @foreach($strategies as $strategy)
            <tr data-id="{{ $strategy->id }}">
                <td class="px-6 py-4 whitespace-nowrap">{{ $strategy->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap name">{{ $strategy->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center rounded-full bg-emerald-50 text-emerald-700 text-xs font-medium py-1 px-3 border border-emerald-100">
                        {{ \App\Models\Strategy::DRIVERS[$strategy->key] }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $strategy->images_count }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ \App\Utils::formatSize($strategy->images_sum_size * 1024) }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                    <a href="{{ route('admin.strategy.edit', ['id' => $strategy->id]) }}" class="text-emerald-600 hover:text-emerald-900">編輯</a>
                    <a href="javascript:void(0)" data-operate="delete" class="text-red-600 hover:text-red-900">刪除</a>
                </td>
            </tr>
            @endforeach
        </x-table>
        @if($strategies->isEmpty())
            <x-no-data message="沒有找到任何儲存策略"/>
        @else
            <div class="mt-4">
                {{ $strategies->links() }}
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            $('[data-operate="delete"]').click(function () {
                Swal.fire({
                    title: `確認刪除儲存策略【${$(this).closest('tr').find('td.name').text()}】嗎?`,
                    text: "如果某個組下面沒有儲存策略，該組下面的使用者將無法上傳圖片，同時已上傳至該儲存的圖片將無法在系統中預覽。",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: '確認刪除',
                }).then((result) => {
                    if (result.isConfirmed) {
                        let id = $(this).closest('tr').data('id');
                        axios.delete(`/admin/strategies/${id}`).then(response => {
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
