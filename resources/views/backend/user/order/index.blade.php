@extends('backend.user.master')
@section('content')
    <div class="bg-cyber-surface border-4 border-black pixel-shadow flex flex-col h-full">
        <!-- Terminal Title Bar -->
        <div class="bg-black p-3 border-b-4 border-black flex justify-between items-center">
            <span class="text-brand font-bold pixel-text text-sm">ORDER_HISTORY.EXE</span>
            <div class="flex gap-2">
                <div class="w-3 h-3 bg-red-500 border border-black"></div>
                <div class="w-3 h-3 bg-yellow-500 border border-black"></div>
                <div class="w-3 h-3 bg-green-500 border border-black"></div>
            </div>
        </div>

        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-2 border-black">
                            <th class="p-4 pixel-text text-xs font-bold text-text-secondary">#ID</th>
                            <th class="p-4 pixel-text text-xs font-bold text-text-secondary">KHÓA HỌC_MODULE</th>
                            <th class="p-4 pixel-text text-xs font-bold text-text-secondary">TRẠNG THÁI</th>
                            <th class="p-4 pixel-text text-xs font-bold text-text-secondary">REFUND_SYNC</th>
                            <th class="p-4 pixel-text text-xs font-bold text-text-secondary">SỐ TIỀN_CREDITS</th>
                            <th class="p-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/10">
                        @forelse($orders as $order)
                            <tr class="group hover:bg-black/20 transition-colors">
                                <td class="p-4 font-mono text-brand font-bold">#{{ $order->id }}</td>
                                <td class="p-4">
                                    <p class="font-bold text-sm">{{ $order->course->course_name ?? $order->course_title }}</p>
                                    <p class="text-[10px] text-text-secondary pixel-text uppercase">Module Path: root/courses/{{ $order->course_id ?? 'null' }}</p>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-1 border border-black text-[10px] font-bold uppercase {{ $order->status === 'completed' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <span class="text-[10px] pixel-text {{ $order->refund_status === 'none' ? 'text-text-secondary' : 'text-cyber-cyan' }}">
                                        {{ strtoupper($order->refund_status) }}
                                    </span>
                                </td>
                                <td class="p-4 font-pixel text-brand font-bold">
                                    {{ number_format($order->gross_amount ?? ($order->price ?? 0), 0, ',', '.') }}đ
                                </td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('user.orders.show', $order) }}"
                                        class="bg-cyber-cyan text-black px-4 py-2 border-2 border-black pixel-shadow text-[10px] font-black pixel-button-hover uppercase">
                                        VIEW_DETAILS
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-10 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <i class="fas fa-ghost text-4xl text-text-secondary opacity-20"></i>
                                        <p class="pixel-text text-text-secondary text-sm">NO_DATA_FOUND: Order history is empty.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $orders->links() }}
            </div>
        </div>
    </div>

    <!-- ===== FOOTER STATUS LINE ===== -->
    <div
        class="mt-10 border-t-2 border-black/30 pt-4 flex justify-between items-center text-[10px] pixel-text text-text-secondary">
        <p>BUILD_VERSION_V2.0_STACKLEARN</p>
        <p>LAST_SYNCED: {{ now()->format('H:i:s') }}_UTC</p>
    </div>
@endsection
