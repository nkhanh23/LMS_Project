<section class="max-w-7xl mx-auto px-6 py-16">
    <div class="text-center mb-12 fade-up">
        <h2 class="font-pixel text-xl lg:text-2xl text-brand mb-4">POPULAR CATEGORIES</h2>
        <p class="text-text-secondary max-w-xl mx-auto">Choose your path. Each category is a new adventure.</p>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        @foreach ($all_category as $item)
            <a href="#"
                class="bg-cyber-surface border-2 border-black p-6 text-center pixel-shadow pixel-button-hover fade-up group">
                {{-- Category Image --}}
                <div
                    class="w-20 h-20 mx-auto mb-3 rounded-full overflow-hidden border-2 border-brand/30 group-hover:border-brand transition-colors duration-300">
                    @if ($item->image)
                        <img src="{{ $item->image }}" alt="{{ $item->name }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    @else
                        <div class="w-full h-full bg-brand/10 flex items-center justify-center">
                            <i class="fas fa-folder-open text-2xl text-brand/50"></i>
                        </div>
                    @endif
                </div>
                <p class="font-bold text-sm">{{ $item->name }}</p>
                <span class="text-text-secondary text-xs">0 Stacks</span>
            </a>
        @endforeach
    </div>
</section>
