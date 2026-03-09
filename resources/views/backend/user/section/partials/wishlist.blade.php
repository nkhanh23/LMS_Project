@php
    if (!isset($wishlist)) {
        $wishlist = auth()->check() ? getWishlist() : collect();
    }
@endphp
<div class="relative group/wish">
    <button
        class="relative w-12 h-12 bg-cyber-surface border-2 border-black pixel-shadow-sm pixel-button-hover flex items-center justify-center">
        <i class="fas fa-heart"></i>
        <span
            class="absolute -top-1 -right-1 bg-pink-500 text-white text-[9px] font-bold w-5 h-5 flex items-center justify-center border border-black">{{ $wishlist->count() }}</span>
    </button>
    <!-- Wishlist Dropdown -->
    <div class="absolute top-full right-0 pt-2 w-80 hidden group-hover/wish:block z-50">
        <div class="bg-cyber-surface border-2 border-black pixel-shadow">
            <div class="px-4 py-3 border-b-2 border-black">
                <h4 class="font-bold text-sm pixel-text text-pink-400">My Wishlist</h4>
            </div>
            <div class="max-h-72 overflow-y-auto">
                @forelse($wishlist as $item)
                    <div
                        class="flex items-center gap-3 px-4 py-3 border-b border-black/30 hover:bg-white/5 transition-colors">
                        <div
                            class="w-14 h-10 bg-cyber-dark border border-black flex items-center justify-center shrink-0">
                            <img src="{{ asset($item->course->course_image) }}" alt=""
                                class="w-full h-full object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold truncate">{{ $item->course->course_name }}</p>
                            <p class="text-pink-400 text-sm font-bold">VNĐ
                                {{ number_format($item->course->selling_price) }}</p>
                        </div>
                        <button
                            class="bg-brand text-black px-2 py-1 text-[10px] font-bold border border-black hover:brightness-110">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </div>
                @empty
                    <div class="px-4 py-3 border-b border-black/30 hover:bg-white/5 transition-colors">
                        <p class="text-sm font-bold truncate">Không có khóa học nào trong wishlist</p>
                    </div>
                @endforelse
            </div>
            <a href="{{ route('user.wishlist.index') }}"
                class="block px-4 py-3 text-center text-xs font-bold text-pink-400 pixel-text hover:bg-white/5 transition-colors border-t-2 border-black">
                Xem tất cả
            </a>
        </div>
    </div>
</div>
