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

        <div class="p-4 sm:p-6">
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-2 border-black">
                            <th class="p-4 pixel-text text-[10px] font-bold text-text-secondary uppercase">#ID</th>
                            <th class="p-4 pixel-text text-[10px] font-bold text-text-secondary uppercase">Course_Module</th>
                            <th class="p-4 pixel-text text-[10px] font-bold text-text-secondary uppercase text-center">Status</th>
                            <th class="p-4 pixel-text text-[10px] font-bold text-text-secondary uppercase text-center">Refund_Sync</th>
                            <th class="p-4 pixel-text text-[10px] font-bold text-text-secondary uppercase text-right">Amount_Credits</th>
                            <th class="p-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-black/10">
                        @forelse($orders as $order)
                            <tr class="group hover:bg-black/20 transition-colors">
                                <td class="p-4 font-mono text-brand font-bold">#{{ $order->id }}</td>
                                <td class="p-4">
                                    <p class="font-bold text-sm">{{ $order->course->course_name ?? $order->course_title }}</p>
                                    <p class="text-[9px] text-text-secondary pixel-text uppercase">Module: root/courses/{{ $order->course_id ?? 'null' }}</p>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-2 py-0.5 border border-black text-[9px] font-bold uppercase {{ $order->status === 'completed' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="text-[9px] pixel-text {{ $order->refund_status === 'none' ? 'text-text-secondary' : 'text-cyber-cyan' }}">
                                        {{ strtoupper($order->refund_status) }}
                                    </span>
                                </td>
                                <td class="p-4 font-pixel text-brand font-bold text-right">
                                    {{ number_format($order->gross_amount ?? ($order->price ?? 0), 0, ',', '.') }}đ
                                </td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('user.orders.show', $order) }}"
                                        class="inline-block bg-cyber-cyan text-black px-3 py-1.5 border-2 border-black pixel-shadow text-[10px] font-black pixel-button-hover uppercase">
                                        VIEW
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="p-10 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <i class="fas fa-ghost text-4xl text-text-secondary opacity-20"></i>
                                        <p class="pixel-text text-text-secondary text-xs uppercase">NO_DATA_FOUND</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile View -->
            <div class="sm:hidden space-y-4">
                @forelse($orders as $order)
                    <div class="bg-black/30 border-2 border-black p-4 space-y-3 relative overflow-hidden">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-brand font-mono text-xs font-bold">#{{ $order->id }}</p>
                                <h4 class="font-bold text-sm text-white mt-1 leading-tight">{{ $order->course->course_name ?? $order->course_title }}</h4>
                            </div>
                            <span class="px-2 py-0.5 border border-black text-[8px] font-bold uppercase {{ $order->status === 'completed' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                                {{ $order->status }}
                            </span>
                        </div>

                        <div class="flex justify-between items-end border-t border-black/30 pt-3">
                            <div class="space-y-1">
                                <p class="text-[8px] text-text-secondary pixel-text uppercase">Sync Status</p>
                                <p class="text-[9px] font-bold {{ $order->refund_status === 'none' ? 'text-text-secondary' : 'text-cyber-cyan' }}">
                                    REFUND: {{ strtoupper($order->refund_status) }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-brand font-pixel font-bold text-lg">
                                    {{ number_format($order->gross_amount ?? ($order->price ?? 0), 0, ',', '.') }}đ
                                </p>
                                <a href="{{ route('user.orders.show', $order) }}" class="inline-block mt-2 text-cyber-cyan text-[10px] font-bold uppercase underline">
                                    VIEW_DETAILS_&raquo;
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center">
                        <p class="pixel-text text-text-secondary text-xs uppercase">NO_DATA_FOUND</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8 border-t-2 border-black/20 pt-6 flex justify-center">
                {{ $orders->links('vendor.pagination.cyber-pixel') }}
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
