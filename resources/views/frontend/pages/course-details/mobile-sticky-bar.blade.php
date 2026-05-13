<div class="fixed bottom-0 left-0 w-full bg-cyber-dark border-t-2 border-brand z-[100] p-4 lg:hidden transform translate-y-full transition-transform duration-300 shadow-[0_-4px_10px_rgba(0,0,0,0.5)]" id="mobileStickyBar">
    <div class="flex items-center justify-between gap-4">
        <div class="flex flex-col">
            <span class="text-2xl font-black text-brand">{{ $course->discount_price }}</span>
            <span class="text-xs text-slate-400 line-through">{{ $course->selling_price }}</span>
        </div>
        <div class="flex-1 flex gap-2">
            <button class="flex-1 bg-brand text-black font-black py-3 px-2 text-xs uppercase border-2 border-black pixel-shadow">
                Add to Cart
            </button>
            <button class="bg-cyber-surface text-white p-3 border-2 border-slate-700">
                <i class="far fa-heart"></i>
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('scroll', function() {
        const bar = document.getElementById('mobileStickyBar');
        const purchaseCard = document.querySelector('.sidebar');
        if (!bar || !purchaseCard) return;

        const purchaseCardRect = purchaseCard.getBoundingClientRect();
        
        // Show bar if the main purchase card is not visible
        if (purchaseCardRect.bottom < 0 || purchaseCardRect.top > window.innerHeight) {
            bar.classList.remove('translate-y-full');
        } else {
            bar.classList.add('translate-y-full');
        }
    });
</script>
