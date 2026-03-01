<section class="max-w-7xl mx-auto px-6 py-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        @foreach ($all_info as $item)
            <div
                class="bg-cyber-surface border-2 border-black pixel-shadow p-6 flex items-start gap-4 pixel-button-hover cursor-default fade-up">
                <div
                    class="w-14 h-14 bg-brand/20 border-2 border-brand flex items-center justify-center shrink-0 text-brand [&>svg]:w-7 [&>svg]:h-7 [&>svg]:fill-current">
                    {!! $item->icon !!}
                </div>
                <div>
                    <h3 class="font-pixel text-sm text-brand mb-2">{{ $item->title }}</h3>
                    <p class="text-text-secondary text-sm">{{ $item->description }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
