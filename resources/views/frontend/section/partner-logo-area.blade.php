<section class="max-w-7xl mx-auto px-6 py-16">
    <h2 class="font-pixel text-sm text-text-secondary mb-8 text-center fade-up">ĐƯỢC TIN TƯỞNG BỞI CÁC CÔNG TY HÀNG ĐẦU
    </h2>
    <div class="owl-carousel client-carousel items-center">
        @foreach ($all_partners as $item)
            <div class="flex items-center justify-center h-16 opacity-40 hover:opacity-100 transition-opacity">
                <span class="font-pixel text-xl text-text-secondary">{{ $item->name }}</span>
            </div>
        @endforeach


    </div>
</section>
