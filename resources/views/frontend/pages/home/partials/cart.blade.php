{{-- @php
    if (!isset($cart)) {
        $cart = getCartItems();
    }
@endphp --}}
<div class="p-3 border-b border-black/30">
    @if ($cart->count() > 0)
        @foreach ($cart as $item)
            <div class="flex items-center gap-3 mb-3">
                <div class="w-12 h-12 bg-cyber-dark border border-black flex-shrink-0">
                    <img src="{{ asset($item->course->course_image) }}" alt="" class="w-full h-full object-cover">
                </div>
                <div class="flex-1 overflow-hidden">
                    <p class="text-sm font-bold truncate">{{ $item->course->course_name }}</p>
                    <p class="text-brand text-sm font-bold">VNĐ
                        {{ number_format($item->course->discount_price, 0, ',', '.') }}</p>
                    @if ($item->course->selling_price > $item->course->discount_price)
                        <span class="text-xs text-gray-500 line-through">VNĐ
                            {{ number_format($item->course->selling_price, 0, ',', '.') }}</span>
                    @endif
                </div>
                <button class="text-red-500 hover:text-red-400 remove-course-btn" data-id="{{ $item->id }}">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endforeach
    @else
        <div class="flex items-center gap-3 mb-3">
            <div class="flex-1">
                <p class="text-sm font-bold truncate">Giỏ hàng trống</p>
            </div>
        </div>
    @endif
</div>
<div class="p-3 flex justify-between items-center">
    <span class="font-bold">Thành tiền: <span class="text-brand">VNĐ
            {{ number_format($subTotal, 0, ',', '.') }}</span></span>
    <a href="{{ route('checkout') }}" class="bg-brand text-black px-4 py-2 text-xs font-bold uppercase pixel-border">Xem
        giỏ
        hàng</a>
</div>
