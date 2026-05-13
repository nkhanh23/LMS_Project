<section class="relative overflow-hidden">
    <div class="owl-carousel hero-slider">
        @foreach ($all_slider as $item)
            <div
                class="relative min-h-[500px] lg:min-h-[600px] flex items-center bg-gradient-to-br from-cyber-dark via-cyber-surface to-cyber-dark">
                <div class="absolute inset-0 opacity-10"
                    style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%228%22 height=%228%22><rect width=%228%22 height=%228%22 fill=%22none%22/><rect width=%224%22 height=%224%22 fill=%22%23A6E22E%22/></svg>');">
                </div>
                <div class="max-w-7xl mx-auto px-6 py-16 flex flex-col lg:flex-row items-center gap-12 relative z-10">
                    <div class="lg:w-1/2 space-y-6">
                        <h1 class="text-4xl md:text-6xl lg:text-7xl font-black leading-tight uppercase break-words">
                            {{ $item->title }}
                        </h1>
                        <p class="text-lg text-text-secondary max-w-lg font-mono">{{ $item->short_description }}</p>
                        <div class="flex flex-wrap gap-4 pt-2">
                            <a href=""
                                class="bg-brand text-black font-bold text-base py-3 px-8 border-3 border-black pixel-shadow pixel-button-hover uppercase">Start
                                Quest <i class="fas fa-play ml-2"></i></a>
                            <a data-fancybox href="{{ $item->video_url }}"
                                class="bg-transparent text-white font-bold text-base py-3 px-8 border-2 border-white hover:bg-white hover:text-black transition-colors uppercase"><i
                                    class="fas fa-play-circle mr-2"></i>Preview</a>
                        </div>
                    </div>
                    <div class="lg:w-1/2 flex justify-center">
                        <div class="w-full bg-cyber-surface border-4 border-black overflow-hidden">
                            <img src="{{ asset($item->image) }}" alt="{{ $item->title }}"
                                class="w-full h-[400px] object-contain"
                                @if ($loop->first) fetchpriority="high" @else loading="lazy" @endif>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>
