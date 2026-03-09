<div class="p-3 border-b border-black/30">
    @forelse ($wishlistItems as $item)
        <div class="flex items-center gap-3 mb-3">
            <div class="w-12 h-12 bg-cyber-dark border border-black">
                <img src="{{ asset($item->course->course_image) }}" alt="" class="w-full h-full object-cover">
            </div>
            <div class="flex-1">
                <p class="text-sm font-bold truncate">{{ $item->course->name }}</p>
                <p class="text-brand text-sm font-bold">{{ $item->course->selling_price }}</p>
                <p class="text-brand text-sm line-through">{{ $item->course->discount_price }}</p>

            </div>
            <button class="text-red-500 hover:text-red-400"><i class="fas fa-times"></i></button>
        </div>
    @empty
        <div class="flex items-center gap-3 mb-3">
            <div class="flex-1">
                <p class="text-sm font-bold truncate">Danh sách yêu thích trống</p>
            </div>
        </div>
    @endforelse

</div>
<div class="p-3 flex justify-between items-center">
    <span class="font-bold">Total: <span class="text-brand">$104.98</span></span>
    <a href="#" class="bg-brand text-black px-4 py-2 text-xs font-bold uppercase pixel-border">Checkout</a>
</div>
