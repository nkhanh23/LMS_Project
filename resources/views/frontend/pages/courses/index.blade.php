@extends('frontend.master')
@section('content')

    <main>
        <section class="max-w-7xl mx-auto px-6 py-10">
            <!-- Header -->
            <div class="mb-10 fade-up">
                <h1 class="font-pixel text-2xl lg:text-3xl text-brand mb-4">Tất cả các khóa học</h1>
                <p class="text-text-secondary font-mono">Khám phá tất cả các dịch vụ của StackLearn dành cho học tập và phát
                    triển kỹ năng</p>
            </div>

            <!-- Main Content Grid -->
            <div class="flex flex-col lg:flex-row gap-8">

                <!-- Left Sidebar: Filters -->
                <aside class="w-full lg:w-1/4 flex-shrink-0 fade-up">
                    <form action="{{ route('frontend.courses.index') }}" method="GET" id="filterForm">
                        <input type="hidden" name="sort" id="sortInput" value="{{ request('sort', 'relevant') }}">

                        <div class="bg-cyber-surface border-2 border-black pixel-shadow p-4 mb-6 lg:hidden group active:translate-y-1 transition-transform">
                            <div
                                class="font-bold flex items-center justify-between cursor-pointer toggle-mobile-filters">
                                <span class="flex items-center gap-3 text-brand">
                                    <i class="fas fa-sliders-h"></i> 
                                    <span class="uppercase tracking-widest text-xs">Bộ lọc & Phân loại</span>
                                </span>
                                <div class="bg-black/40 w-8 h-8 flex items-center justify-center border border-slate-700">
                                    <i class="fas fa-chevron-down text-[10px] transition-transform filter-chevron"></i>
                                </div>
                            </div>
                        </div>

                        <div class="filter-container hidden lg:block">
                            <!-- Rating Filter -->
                            <div class="mb-8 border-t-2 border-black/30 pt-6">
                                <h3 class="font-bold text-lg mb-4 flex items-center justify-between cursor-pointer">
                                    Xếp hạng <i class="fas fa-chevron-up text-xs"></i>
                                </h3>
                                <div class="space-y-3">
                                    @foreach ([4.5, 4.0, 3.5, 3.0] as $rating)
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="radio" name="rating" value="{{ $rating }}"
                                                {{ request('rating') == $rating ? 'checked' : '' }}
                                                onchange="document.getElementById('filterForm').submit();"
                                                class="appearance-none w-4 h-4 border-2 border-slate-500 rounded-full checked:bg-brand checked:border-brand transition-colors relative after:content-[''] after:absolute after:hidden checked:after:block after:left-[4px] after:top-[4px] after:w-[4px] after:h-[4px] after:rounded-full after:bg-black">
                                            <div class="flex text-yellow-400 text-xs">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($rating))
                                                        <i class="fas fa-star"></i>
                                                    @elseif($i == ceil($rating))
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span
                                                class="text-sm text-text-secondary group-hover:text-brand transition-colors">Từ
                                                {{ $rating }} trở lên</span>
                                        </label>
                                    @endforeach
                                </div>
                                @if (request('rating'))
                                    <div class="mt-4">
                                        <a href="{{ route('frontend.courses.index', ['sort' => request('sort')]) }}"
                                            class="text-xs text-brand hover:underline">Xóa bộ lọc</a>
                                    </div>
                                @endif
                            </div>



                            <!-- Category Filter -->
                            <div class="mb-8 border-t-2 border-black/30 pt-6">
                                <h3 class="font-bold text-lg mb-4 flex items-center justify-between cursor-pointer">
                                    Danh mục <i class="fas fa-chevron-up text-xs"></i>
                                </h3>
                                <div class="space-y-3 max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
                                    @foreach ($categories as $category)
                                        <!-- Danh mục cha -->
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="radio" name="category" value="{{ $category->id }}"
                                                {{ request('category') == $category->id ? 'checked' : '' }}
                                                onchange="document.getElementById('filterForm').submit();"
                                                class="appearance-none w-4 h-4 border-2 border-slate-500 rounded-full checked:bg-brand checked:border-brand transition-colors relative after:content-[''] after:absolute after:hidden checked:after:block after:left-[4px] after:top-[4px] after:w-[4px] after:h-[4px] after:rounded-full after:bg-black">
                                            <span
                                                class="text-sm font-bold {{ request('category') == $category->id ? 'text-brand' : 'text-text-primary' }} group-hover:text-brand transition-colors">
                                                {{ $category->name }}
                                            </span>
                                        </label>

                                        <!-- Hiển thị danh mục con nếu danh mục cha đang được chọn -->
                                        @if (request('category') == $category->id && $category->subcategory->isNotEmpty())
                                            <div class="ml-7 space-y-2 mt-2 border-l-2 border-slate-700 pl-4">
                                                <!-- Tùy chọn bỏ lọc danh mục con -->
                                                <label class="flex items-center gap-3 cursor-pointer group">
                                                    <input type="radio" name="subcategory" value=""
                                                        {{ !request('subcategory') ? 'checked' : '' }}
                                                        onchange="document.getElementById('filterForm').submit();"
                                                        class="appearance-none w-3.5 h-3.5 border-2 border-slate-500 rounded-full checked:bg-brand checked:border-brand transition-colors relative after:content-[''] after:absolute after:hidden checked:after:block after:left-[3px] after:top-[3px] after:w-[3px] after:h-[3px] after:rounded-full after:bg-black">
                                                    <span
                                                        class="text-xs text-text-secondary group-hover:text-brand transition-colors">
                                                        Tất cả {{ $category->name }}
                                                    </span>
                                                </label>

                                                <!-- Danh sách danh mục con -->
                                                @foreach ($category->subcategory as $sub)
                                                    <label class="flex items-center gap-3 cursor-pointer group">
                                                        <input type="radio" name="subcategory"
                                                            value="{{ $sub->id }}"
                                                            {{ request('subcategory') == $sub->id ? 'checked' : '' }}
                                                            onchange="document.getElementById('filterForm').submit();"
                                                            class="appearance-none w-3.5 h-3.5 border-2 border-slate-500 rounded-full checked:bg-brand checked:border-brand transition-colors relative after:content-[''] after:absolute after:hidden checked:after:block after:left-[3px] after:top-[3px] after:w-[3px] after:h-[3px] after:rounded-full after:bg-black">
                                                        <span
                                                            class="text-xs {{ request('subcategory') == $sub->id ? 'text-white' : 'text-text-secondary' }} group-hover:text-brand transition-colors">
                                                            {{ $sub->name }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <!-- Nút Clear Filter Danh mục -->
                                @if (request('category') || request('subcategory'))
                                    <div class="mt-4">
                                        <a href="{{ route('frontend.courses.index', ['sort' => request('sort'), 'rating' => request('rating')]) }}"
                                            class="text-xs text-brand hover:underline font-bold">
                                            <i class="fas fa-times mr-1"></i> Xóa lọc danh mục
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                </aside>

                <!-- Right Content: Course List -->
                <div class="w-full lg:w-3/4 flex-grow fade-up">

                    <!-- Controls Bar -->
                    <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4">
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <!-- Desktop Filter Button (visual only to match Udemy layout) -->
                            <div
                                class="hidden lg:flex bg-cyber-surface border-2 border-black px-4 py-[10px] font-bold text-sm items-center gap-2">
                                <i class="fas fa-filter"></i> Bộ lọc
                            </div>

                            <!-- Sort By -->
                            <div class="relative w-full sm:w-48">
                                <div class="text-xs text-text-secondary absolute -top-5 left-0">Sắp xếp theo</div>
                                <select form="filterForm" name="sort_ui"
                                    onchange="document.getElementById('sortInput').value = this.value; document.getElementById('filterForm').submit();"
                                    class="w-full appearance-none bg-cyber-surface border-2 border-black px-4 py-3 text-sm font-bold focus:outline-none focus:border-brand cursor-pointer">
                                    <option value="relevant" {{ request('sort') == 'relevant' ? 'selected' : '' }}>Liên
                                        quan nhất</option>
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất
                                    </option>
                                    <option value="highest_rated"
                                        {{ request('sort') == 'highest_rated' ? 'selected' : '' }}>Đánh giá cao nhất
                                    </option>
                                </select>
                                <i
                                    class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-xs"></i>
                            </div>
                        </div>

                        <div class="text-text-secondary font-bold text-sm sm:text-right w-full sm:w-auto">
                            {{ number_format($courses->total(), 0, ',', '.') }} kết quả
                        </div>
                    </div>

                    <!-- Course Items -->
                    <div class="space-y-4">
                        @forelse($courses as $course)
                            <article
                                class="group relative flex flex-col sm:flex-row gap-4 py-4 border-b border-black/30 hover:bg-cyber-surface/50 transition-colors p-2 rounded">
                                <!-- Image -->
                                <a href="{{ route('chi-tiet', $course->course_name_slug) }}"
                                    class="flex-shrink-0 w-full sm:w-64 h-36 bg-cyber-dark border-2 border-black relative overflow-hidden block">
                                    <img loading="lazy" src="{{ asset($course->course_image) }}"
                                        alt="{{ $course->course_name }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    @if ($course->bestseller === 'yes')
                                        <span
                                            class="absolute top-2 left-2 bg-yellow-400 text-black text-[9px] font-bold px-2 py-0.5 border border-black">Bestseller</span>
                                    @endif
                                </a>

                                <!-- Details -->
                                <div class="flex-grow flex flex-col justify-between min-w-0">
                                    <div class="mb-2">
                                        <div class="flex flex-col sm:flex-row justify-between items-start gap-2 mb-2">
                                            <h3 class="font-bold text-base sm:text-lg leading-tight w-full sm:w-3/4">
                                                <a href="{{ route('chi-tiet', $course->course_name_slug) }}"
                                                    class="hover:text-brand transition-colors line-clamp-2">
                                                    {{ $course->course_name }}
                                                </a>
                                            </h3>

                                            <div class="flex flex-row sm:flex-col items-center sm:items-end gap-2 w-full sm:w-1/4 sm:text-right shrink-0">
                                                <div class="text-brand font-bold text-base whitespace-nowrap">
                                                    {{ number_format($course->discount_price, 0, ',', '.') }} ₫
                                                </div>
                                                @if ($course->selling_price > $course->discount_price)
                                                    <div class="text-text-secondary text-[10px] sm:text-xs line-through">
                                                        {{ number_format($course->selling_price, 0, ',', '.') }} ₫
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <p class="text-text-secondary text-xs mb-2 line-clamp-1 hidden sm:block">
                                            {{ strip_tags($course->description) }}
                                        </p>

                                        <p class="text-[11px] text-text-secondary mb-1">
                                            {{ $course->user->name ?? 'Instructor' }}
                                        </p>

                                        <div class="flex items-center gap-1">
                                            <span class="text-yellow-400 font-bold text-xs">
                                                {{ number_format($course->reviews_avg_rating ?? 0, 1) }}
                                            </span>
                                            <div class="text-yellow-400 text-[10px] flex">
                                                @php
                                                    $rating = $course->reviews_avg_rating ?? 0;
                                                @endphp
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= floor($rating))
                                                        <i class="fas fa-star"></i>
                                                    @elseif($i == ceil($rating) && $rating - floor($rating) >= 0.5)
                                                        <i class="fas fa-star-half-alt"></i>
                                                    @else
                                                        <i class="far fa-star"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-[10px] text-text-secondary">
                                                ({{ number_format($course->reviews_count ?? 0) }})
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 text-[10px] sm:text-[11px] text-text-secondary flex-wrap border-t border-black/10 pt-2">
                                        @php
                                            $total_minutes = $course->sections->flatMap->lecture->sum('video_duration');
                                            $hours = floor($total_minutes / 60);
                                            $minutes = floor($total_minutes % 60);
                                            $lecture_count = $course->sections->flatMap->lecture->count();
                                        @endphp
                                        <span>
                                            {{ $hours > 0 ? $hours . ' giờ' : '' }} {{ $minutes }} phút
                                        </span>
                                        <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                                        <span>{{ $lecture_count }} bài giảng</span>
                                        <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                                        <span class="uppercase tracking-tighter">Tất cả trình độ</span>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div
                                class="bg-cyber-surface border-2 border-black pixel-shadow p-10 text-center text-text-secondary">
                                <i class="fas fa-box-open text-4xl mb-4 text-slate-600"></i>
                                <p>Không tìm thấy khóa học nào phù hợp với bộ lọc của bạn.</p>
                                @if (request('rating') || request('sort'))
                                    <a href="{{ route('frontend.courses.index') }}"
                                        class="inline-block mt-4 bg-brand text-black font-bold px-4 py-2 border-2 border-black pixel-button-hover text-sm">Xóa
                                        bộ lọc</a>
                                @endif
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if ($courses->hasPages())
                        <div class="mt-12 border-t-2 border-black/30 pt-10 flex justify-center fade-up">
                            {{ $courses->links('vendor.pagination.cyber-pixel') }}
                        </div>
                    @endif
                </div>
            </div>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile filter toggle
            const toggleBtn = document.querySelector('.toggle-mobile-filters');
            const filterContainer = document.querySelector('.filter-container');
            const chevron = document.querySelector('.filter-chevron');

            if (toggleBtn && filterContainer) {
                toggleBtn.addEventListener('click', function() {
                    filterContainer.classList.toggle('hidden');
                    if (filterContainer.classList.contains('hidden')) {
                        chevron.style.transform = 'rotate(0deg)';
                    } else {
                        chevron.style.transform = 'rotate(180deg)';
                    }
                });
            }
        });
    </script>

@endsection
