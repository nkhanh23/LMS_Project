<section class="max-w-7xl mx-auto px-6 py-8 md:py-16">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-10 gap-4 fade-up">
        <h2 class="font-pixel text-xl lg:text-2xl text-brand">HOT QUESTS</h2>

        <div class="flex gap-2 overflow-x-auto pb-2 w-full md:w-auto no-scrollbar scroll-smooth">
            <button
                class="tab-btn active px-4 py-2 text-xs font-bold uppercase border-2 border-black bg-brand text-black shrink-0"
                data-filter="all">
                All
            </button>

            @foreach ($categories as $index => $item)
                <button
                    class="tab-btn bg-cyber-surface text-text-primary px-4 py-2 text-xs font-bold uppercase border-2 border-black hover:bg-brand hover:text-black transition-colors shrink-0"
                    data-filter="{{ $item->slug }}">
                    {{ $item->name }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="flex md:grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-8 overflow-x-auto md:overflow-x-visible no-scrollbar pb-6 md:pb-0 scroll-smooth min-h-0" id="course-grid">
        @foreach ($course_category as $index => $data)
            @foreach ($data['course'] as $course)
                <div class="course-item course-card-wrap relative group shrink-0 w-[85vw] md:w-auto" data-cat="{{ $data->slug }}">
                    <article
                        class="bg-cyber-surface border-2 border-black pixel-shadow hover:-translate-y-1 transition-transform cursor-pointer h-full flex flex-row md:flex-col">

                        <div class="w-32 md:w-full h-auto md:h-44 bg-cyber-dark border-r-2 md:border-r-0 md:border-b-2 border-black relative overflow-hidden shrink-0">
                            <a href="{{ route('chi-tiet', $course->course_name_slug) }}" class="block w-full h-full">
                                <img loading="lazy" class="w-full h-full object-cover"
                                    src="{{ asset($course->course_image) }}" alt="{{ $course->course_name }}">
                            </a>

                            <div class="absolute top-1 left-1 md:top-2 md:left-2 flex flex-col md:flex-row gap-1 items-start md:items-center flex-wrap max-w-[90%]">
                                <span
                                    class="bg-yellow-400 text-black text-[7px] md:text-[9px] font-bold px-1 md:px-2 py-0.5 border border-black uppercase">
                                    @if ($course->bestseller === 'yes')
                                        Bestseller
                                    @elseif($course->featured === 'yes')
                                        Featured
                                    @elseif($course->highestrated === 'yes')
                                        HighestRated
                                    @else
                                        New
                                    @endif
                                </span>

                                <span class="bg-brand text-black text-[7px] md:text-[9px] font-bold px-1 md:px-2 py-0.5 border border-black">
                                    -{{ round((($course->selling_price - $course->discount_price) / $course->selling_price) * 100) }}%
                                </span>
                            </div>

                            @php
                                if (auth()->check()) {
                                    $user_id = auth()->user()->id;
                                    $isWishlisted = \App\Models\Wishlist::where('user_id', $user_id)
                                        ->where('course_id', $course->id)
                                        ->first();
                                } else {
                                    $isWishlisted = null;
                                }
                            @endphp
                        </div>

                        <div class="p-3 md:p-5 space-y-2 md:space-y-3 flex-1 flex flex-col justify-between min-w-0">
                            <div class="space-y-1 md:space-y-3">
                                <h5 class="text-[10px] md:text-sm text-text-secondary truncate">
                                    <i class="fas fa-user mr-1 text-cyber-cyan"></i>
                                    {{ $course->label }}
                                </h5>

                                <h3 class="font-bold text-sm md:text-lg leading-tight md:leading-snug min-h-0 md:min-h-[56px] line-clamp-2">
                                    <a href="#">
                                        {{ $course->course_name }}
                                    </a>
                                </h3>

                                <div class="text-yellow-400 text-[10px] md:text-sm">
                                    ★★★★☆ <span class="text-text-secondary">(4.4)</span>
                                </div>
                            </div>

                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pt-1 gap-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-brand font-bold text-sm md:text-xl">
                                        VNĐ {{ $course->discount_price }}
                                    </span>

                                    <span class="text-[10px] md:text-sm text-text-secondary line-through">
                                        VNĐ {{ $course->selling_price }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-1 self-end sm:self-auto">
                                    <button class="lg:hidden text-base md:text-lg p-1.5 md:p-2 info-btn hover:scale-110 transition-transform"
                                        title="Xem chi tiết" onclick="window.openMobileTooltip(this, event)">
                                        <i class="fas fa-info-circle text-cyber-cyan"></i>
                                    </button>

                                    <button class="text-base md:text-lg hover:scale-110 transition-transform wishlist-icon p-1.5 md:p-2"
                                        title="Thêm vào danh sách yêu thích" data-course-id="{{ $course->id }}">
                                        @if ($isWishlisted)
                                            <i class="fas fa-heart text-red-600"></i>
                                        @else
                                            <i class="far fa-heart text-white"></i>
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>
                    </article>

                    @include('frontend.section.tooltip')
                </div>
            @endforeach
        @endforeach
    </div>
</section>

<script>
    // ===== Pure Vanilla JS - No jQuery needed =====

    // Helper: close a tooltip element
    function hideTooltip(el) {
        el.style.display = 'none';
        el.classList.add('hidden');
        el.classList.remove('flex');
        document.body.style.overflow = '';
    }

    // Helper: show a tooltip element
    function showTooltip(el) {
        el.style.display = 'flex';
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    // Open tooltip (called from inline onclick on info-btn)
    window.openMobileTooltip = function(btn, e) {
        e.preventDefault();
        e.stopPropagation();

        // Close all open tooltips first
        document.querySelectorAll('.course-tooltip').forEach(function(t) {
            hideTooltip(t);
        });

        // Find the tooltip inside the same .course-item
        var courseItem = btn.closest('.course-item');
        if (courseItem) {
            var tooltip = courseItem.querySelector('.course-tooltip');
            if (tooltip) {
                showTooltip(tooltip);
            }
        }
    };

    // Close tooltip via X button (called from inline onclick on close-tooltip)
    window.closeMobileTooltip = function(btn, e) {
        e.preventDefault();
        e.stopPropagation();

        var tooltip = btn.closest('.course-tooltip');
        if (tooltip) {
            hideTooltip(tooltip);
        }
    };

    // Close tooltip via overlay background click (called from inline onclick on .course-tooltip)
    window.closeTooltipOverlay = function(overlay, e) {
        // Only close if clicking directly on the overlay, not its children
        if (e.target === overlay) {
            hideTooltip(overlay);
        }
    };

    // Tab Filtering (uses DOMContentLoaded - no jQuery needed)
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.tab-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var filter = this.getAttribute('data-filter');

                document.querySelectorAll('.tab-btn').forEach(function(btn) {
                    btn.classList.remove('active', 'bg-brand', 'text-black');
                    btn.classList.add('bg-cyber-surface', 'text-text-primary');
                });

                this.classList.add('active', 'bg-brand', 'text-black');
                this.classList.remove('bg-cyber-surface', 'text-text-primary');

                document.querySelectorAll('.course-item').forEach(function(item) {
                    var cat = item.getAttribute('data-cat');
                    if (filter === 'all' || cat === filter) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            });
        });

        // Adjust tooltip position on hover for desktop (lg screens)
        document.querySelectorAll('.course-card-wrap').forEach(function(card) {
            var tooltip = card.querySelector('.course-tooltip');
            if (!tooltip) return;

            card.addEventListener('mouseenter', function() {
                if (window.innerWidth >= 1024) {
                    var cardRect = card.getBoundingClientRect();
                    var tooltipWidth = 320; // Matches lg:w-80 (320px)
                    var padding = 12;      // Matches lg:pl-3 (12px)

                    if (cardRect.right + tooltipWidth + padding > window.innerWidth) {
                        // Flip to left side
                        tooltip.style.left = 'auto';
                        tooltip.style.right = '100%';
                        tooltip.style.paddingLeft = '0px';
                        tooltip.style.paddingRight = '12px';
                    } else {
                        // Align to right side (default)
                        tooltip.style.left = '100%';
                        tooltip.style.right = 'auto';
                        tooltip.style.paddingLeft = '12px';
                        tooltip.style.paddingRight = '0px';
                    }
                }
            });
        });
    });
</script>
