<section class="mt-16 border-t-2 border-slate-800 pt-16 relative">
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
    <h2 class="text-2xl font-bold uppercase tracking-tighter text-brand mb-8 pixel-text">Các khóa học khác của
        {{ $course->user->name }}</h2>
    <div id="related-courses-slider" class="flex overflow-x-auto gap-6 pb-4 snap-x no-scrollbar drag-scroll">
        @forelse ($more_course_instructor as $course_item)
            <div
                class="shrink-0 w-full md:w-[calc(50%-0.75rem)] xl:w-[calc(33.333333%-1rem)] snap-start bg-cyber-surface border-2 border-black pixel-shadow hover:-translate-y-1 transition-transform group p-4">
                <div class="h-36 bg-cyber-dark flex items-center justify-center mb-3 relative">
                    <img loading="lazy" src="{{ asset($course_item->course_image) }}" alt="{{ $course_item->course_name }}"
                        class="w-full h-full object-cover">
                    <span class="absolute top-2 left-2 bg-pink-500 text-white text-[10px] px-2 py-0.5 font-bold">
                        @if ($course_item->bestseller == 'yes')
                            Bestseller
                        @elseif ($course_item->featured == 'yes')
                            Featured
                        @else
                            HighestRated
                        @endif
                    </span>
                </div>
                <h4 class="font-bold text-sm text-slate-100 group-hover:text-brand mb-1">
                    {{ \Illuminate\Support\Str::limit($course_item->course_name, 50) }}
                </h4>
                <p class="text-[10px] text-slate-400 mb-2">{{ $course_item['user']['name'] }}</p>
                <div class="flex items-center gap-1 text-yellow-400 text-xs mb-2">
                    <span>{{ $course_item->rating }}</span> <i class="fas fa-star"></i><i class="fas fa-star"></i><i
                        class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                </div>
                <div class="flex items-center justify-between border-t border-slate-700 pt-2">
                    <span class="font-bold text-brand">VND {{ $course_item->discount_price }}</span>
                    <span class="text-xs text-slate-400">VND {{ $course_item->selling_price }}</span>
                    <button class="text-slate-500 hover:text-pink-400"><i class="far fa-heart"></i></button>
                </div>
            </div>
        @empty
            <div class="w-full text-center text-slate-400">Không có khóa học nào khác của giảng viên này</div>
        @endforelse
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const slider = document.getElementById('related-courses-slider');
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
