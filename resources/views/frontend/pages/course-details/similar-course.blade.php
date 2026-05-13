<section class="py-6 relative">
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .drag-scroll {
            cursor: grab;
        }

        .drag-scroll.dragging {
            cursor: grabbing;
            scroll-snap-type: none !important;
            /* Disable snap when dragging */
        }

        .drag-scroll.dragging>div {
            pointer-events: none;
            /* Prevent clicking inside elements while dragging */
        }
    </style>
    <h3 class="mb-4 text-xl font-bold uppercase tracking-tighter text-brand">Khóa học tương tự</h3>
    <div id="similar-courses-slider" class="flex overflow-x-auto gap-4 pb-4 snap-x no-scrollbar drag-scroll">
        @forelse ($similarCourse as $item)
            <div
                class="shrink-0 w-[calc(80%)] md:w-[calc(50%-0.5rem)] lg:w-[calc(33.333333%-0.66rem)] snap-start bg-cyber-surface border border-slate-700 p-3 hover:border-brand transition-colors group relative">
                <div class="h-32 bg-black flex items-center justify-center mb-3">
                    <a href="{{ route('chi-tiet', $item->course_name_slug) }}">
                        <img class="w-full h-full object-cover" src="{{ asset($item->course_image) }}"
                            alt="{{ $item->course_name }}" loading="lazy">
                    </a>
                </div>
                <span
                    class="absolute top-5 left-5 bg-pink-500 text-white text-[10px] px-2 py-0.5 font-bold z-10">-{{ round((($item->selling_price - $item->discount_price) / $item->selling_price) * 100) }}%</span>
                <span
                    class="absolute top-5 right-5 bg-black/80 text-white text-[10px] px-2 py-0.5 z-10 border border-slate-600">
                    @if ($item->bestseller == 'yes')
                        Bestseller
                    @elseif ($item->featured == 'yes')
                        Fetured
                    @else
                        HighesRated
                    @endif
                </span>
                <h4 class="font-bold text-sm text-slate-100 group-hover:text-brand line-clamp-2">
                    <a href="{{ route('chi-tiet', $item->course_name_slug) }}">
                        {{ $item->course_name }}
                    </a>
                </h4>
                <p class="text-[10px] text-slate-400 mt-1"><a href="">
                        {{ $item['user']['name'] }}
                    </a></p>
                <div class="flex items-center gap-1 text-yellow-400 text-xs mt-1">
                    <span>{{ $item->rating }}</span> <i class="fas fa-star"></i>
                </div>
                <div class="flex items-center justify-between mt-2">
                    <span class="font-bold text-brand">{{ $item->selling_price }}</span>
                    <span class="text-slate-500">{{ $item->discount_price }}</span>
                    <button class="text-slate-500 hover:text-pink-400"><i class="far fa-heart"></i></button>
                </div>
            </div>
        @empty
            <p class="w-full text-slate-400">Không có khóa học nào</p>
        @endforelse
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('similar-courses-slider');
        if (!slider) return;

        let isDown = false;
        let startX;
        let scrollLeft;
        let isHovered = false;

        let scrollSpeed = 1; // Pixels per frame
        let animationId;

        function autoScroll() {
            if (!isHovered && !isDown) {
                slider.scrollLeft += scrollSpeed;
                // Reverse if reached the end logically with some padding
                if (slider.scrollLeft >= (slider.scrollWidth - slider.clientWidth - 1)) {
                    slider.scrollLeft = 0; // Seamless loop or jump to 0
                }
            }
            animationId = requestAnimationFrame(autoScroll);
        }

        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('dragging');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
            isHovered = false;
            slider.classList.remove('dragging');
        });

        slider.addEventListener('mouseenter', () => {
            isHovered = true;
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('dragging');
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2; // Scroll-fast multiplier
            slider.scrollLeft = scrollLeft - walk;
        });

        // Stop auto scroll on touch
        slider.addEventListener('touchstart', () => {
            isHovered = true;
        }, {
            passive: true
        });
        slider.addEventListener('touchend', () => {
            isHovered = false;
        }, {
            passive: true
        });

        // Start animation
        animationId = requestAnimationFrame(autoScroll);
    });
</script>
