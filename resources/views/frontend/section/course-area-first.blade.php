<section class="max-w-7xl mx-auto px-6 py-16">
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-10 gap-4 fade-up">
        <h2 class="font-pixel text-xl lg:text-2xl text-brand">HOT QUESTS</h2>

        <div class="flex flex-wrap gap-2">
            <button
                class="tab-btn active px-4 py-2 text-xs font-bold uppercase border-2 border-black bg-brand text-black"
                data-filter="all">
                All
            </button>

            @foreach ($categories as $index => $item)
                <button
                    class="tab-btn bg-cyber-surface text-text-primary px-4 py-2 text-xs font-bold uppercase border-2 border-black hover:bg-brand hover:text-black transition-colors"
                    data-filter="{{ $item->slug }}">
                    {{ $item->name }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="course-grid">
        @foreach ($course_category as $index => $data)
            @foreach ($data['course'] as $course)
                <div class="course-item course-card-wrap relative group" data-cat="{{ $data->slug }}">
                    <article
                        class="bg-cyber-surface border-2 border-black pixel-shadow hover:-translate-y-1 transition-transform cursor-pointer h-full flex flex-col">

                        <div class="h-44 bg-cyber-dark border-b-2 border-black relative overflow-hidden">
                            <a href="{{ route('course-details', $course->course_name_slug) }}"
                                class="block w-full h-full">
                                <img loading="lazy" class="w-full h-full object-cover"
                                    src="{{ asset($course->course_image) }}" alt="{{ $course->course_name }}">
                            </a>

                            <span
                                class="absolute top-2 left-2 bg-yellow-400 text-black text-[9px] font-bold px-2 py-0.5 border border-black">
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

                            <span
                                class="absolute top-2 left-24 bg-brand text-black text-[9px] font-bold px-2 py-0.5 border border-black">
                                -{{ round((($course->selling_price - $course->discount_price) / $course->selling_price) * 100) }}%
                            </span>

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

                        <div class="p-5 space-y-3 flex-1 flex flex-col justify-between">
                            <div class="space-y-3">
                                <h5 class="text-sm text-text-secondary">
                                    <i class="fas fa-user mr-1 text-cyber-cyan"></i>
                                    {{ $course->label }}
                                </h5>

                                <h3 class="font-bold text-lg leading-snug min-h-[56px]">
                                    <a href="#">
                                        {{ \Illuminate\Support\Str::limit($course->course_name, 50) }}
                                    </a>
                                </h3>

                                <div class="text-yellow-400 text-sm">
                                    ★★★★☆ <span class="text-text-secondary">(4.4)</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="text-brand font-bold text-xl">
                                        VNĐ {{ $course->discount_price }}
                                    </span>

                                    <span class="text-sm text-text-secondary line-through">
                                        VNĐ {{ $course->selling_price }}
                                    </span>
                                </div>

                                <button class="text-lg hover:scale-110 transition-transform wishlist-icon p-2"
                                    title="Thêm vào danh sách yêu thích" data-course-id="{{ $course->id }}">
                                    @if ($isWishlisted)
                                        <i class="fas fa-heart text-red-600"></i>
                                    @else
                                        <i class="far fa-heart text-white"></i>
                                    @endif
                                </button>
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
    document.addEventListener('DOMContentLoaded', function() {
        const buttons = document.querySelectorAll('.tab-btn');
        const items = document.querySelectorAll('.course-item');

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const filter = this.getAttribute('data-filter');

                buttons.forEach(btn => {
                    btn.classList.remove('active', 'bg-brand', 'text-black');
                    btn.classList.add('bg-cyber-surface', 'text-text-primary');
                });

                this.classList.add('active', 'bg-brand', 'text-black');
                this.classList.remove('bg-cyber-surface', 'text-text-primary');

                items.forEach(item => {
                    const cat = item.getAttribute('data-cat');

                    if (filter === 'all' || cat === filter) {
                        item.classList.remove('hidden');
                    } else {
                        item.classList.add('hidden');
                    }
                });
            });
        });
    });
</script>
