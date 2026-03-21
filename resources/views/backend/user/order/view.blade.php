@extends('backend.user.master')
@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Order Details -->
        <div class="lg:col-span-12">
            <div class="bg-cyber-surface border-4 border-black pixel-shadow flex flex-col">
                <!-- Terminal Header -->
                <div class="bg-black p-3 border-b-4 border-black flex justify-between items-center">
                    <span class="text-brand font-bold pixel-text text-sm">ORDER_DETAILS_#{{ $order->id }}.INF</span>
                    <div class="flex gap-2">
                        <div class="w-3 h-3 bg-red-500 border border-black"></div>
                        <div class="w-3 h-3 bg-yellow-500 border border-black"></div>
                        <div class="w-3 h-3 bg-green-500 border border-black"></div>
                    </div>
                </div>

                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-4">
                            <div class="group">
                                <p class="text-text-secondary text-[10px] uppercase font-bold mb-1 pixel-text">Course
                                    Title</p>
                                <p class="text-cyber-cyan text-xl font-bold border-b-2 border-slate-700 pb-1 uppercase">
                                    {{ $order->course->course_name ?? $order->course_title }}
                                </p>
                            </div>
                            <div class="group">
                                <p class="text-text-secondary text-[10px] uppercase font-bold mb-1 pixel-text">Order Status
                                </p>
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-3 h-3 border border-black {{ $order->status === 'completed' ? 'bg-green-500' : 'bg-yellow-500' }}">
                                    </div>
                                    <p class="text-brand font-bold uppercase font-mono">{{ $order->status }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="group">
                                <p class="text-text-secondary text-[10px] uppercase font-bold mb-1 pixel-text">Amount
                                    Credits</p>
                                <p class="text-brand text-2xl font-black font-pixel">
                                    {{ number_format($order->gross_amount ?? ($order->price ?? 0), 0, ',', '.') }}đ
                                </p>
                            </div>
                            <div class="group">
                                <p class="text-text-secondary text-[10px] uppercase font-bold mb-1 pixel-text">Refund Sync
                                </p>
                                <p class="text-text-primary font-bold uppercase font-mono">
                                    {{ $order->refund_status ?? 'NONE' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Refund Request Section -->
        @if ($order->status === 'completed' && !in_array($order->refund_status, ['requested', 'approved', 'processed']))
            <div class="lg:col-span-5">
                <div class="bg-cyber-surface border-4 border-black pixel-shadow flex flex-col h-full">
                    <div class="bg-black p-3 border-b-4 border-black">
                        <span class="text-yellow-400 font-bold pixel-text text-xs uppercase">_REFUND_REQUEST_PROTOCOL</span>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('user.orders.refund.request', $order) }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="group">
                                <label class="text-text-secondary text-[10px] uppercase font-bold mb-1 block pixel-text">Type
                                    Select</label>
                                <select name="type"
                                    class="w-full bg-black border-2 border-slate-700 text-brand font-bold p-2 outline-none focus:border-brand">
                                    <option value="refund">REFUND</option>
                                    <option value="cancel">CANCEL</option>
                                </select>
                            </div>

                            <div class="group">
                                <label
                                    class="text-text-secondary text-[10px] uppercase font-bold mb-1 block pixel-text">Reason
                                    Log</label>
                                <textarea name="reason" rows="3" required
                                    class="w-full bg-black border-2 border-slate-700 text-text-primary p-2 outline-none focus:border-cyber-cyan font-mono text-xs placeholder:opacity-30"
                                    placeholder="INPUT_REASON_HERE...">{{ old('reason') }}</textarea>
                            </div>

                            <div class="group">
                                <label class="text-text-secondary text-[10px] uppercase font-bold mb-1 block pixel-text">Value
                                    Amount</label>
                                <input type="number" step="1" name="requested_amount"
                                    class="w-full bg-black border-2 border-slate-700 text-brand font-bold p-2 outline-none focus:border-brand"
                                    value="{{ $order->gross_amount ?? ($order->price ?? 0) }}">
                            </div>

                            <button type="submit"
                                class="w-full bg-yellow-400 text-black font-black py-3 border-2 border-black pixel-shadow-sm pixel-button-hover uppercase text-xs">
                                INITIATE_REQUEST
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Interaction History -->
        @if ($order->refundRequests->count())
            <div class="{{ $order->status === 'completed' ? 'lg:col-span-7' : 'lg:col-span-12' }}">
                <div class="bg-cyber-surface border-4 border-black pixel-shadow flex flex-col h-full">
                    <div class="bg-black p-3 border-b-4 border-black">
                        <span class="text-white font-bold pixel-text text-xs uppercase">_LOG_EXTRACTS</span>
                    </div>
                    <div class="p-6 space-y-4">
                        @foreach ($order->refundRequests as $item)
                            <div class="bg-black/40 border-l-4 border-brand p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-brand font-bold text-xs font-mono uppercase">[{{ $item->type }}]</span>
                                    <span class="text-[10px] px-2 py-0.5 border border-black {{ $item->status === 'approved' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                                        {{ strtoupper($item->status) }}
                                    </span>
                                </div>
                                <p class="text-xs text-text-secondary mb-2">> REASON: {{ $item->reason }}</p>
                                @if($item->admin_note)
                                    <p class="text-xs text-cyber-cyan italic">> ADMIN_NOTE: {{ $item->admin_note }}</p>
                                @endif
                                <p class="text-[8px] text-text-secondary mt-2 opacity-50 uppercase">Timestamp: {{ $item->created_at->format('Y-m-d H:i') }}_UTC</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- ===== FOOTER STATUS LINE ===== -->
    <div
        class="mt-10 border-t-2 border-black/30 pt-4 flex justify-between items-center text-[10px] pixel-text text-text-secondary">
        <p>BUILD_VERSION_V2.0_STACKLEARN</p>
        <p>LAST_SYNCED: {{ now()->format('H:i:s') }}_UTC</p>
    </div>
@endsection
