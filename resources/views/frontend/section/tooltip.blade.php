<div
    class="course-tooltip hidden lg:group-hover:block absolute left-full top-0 ml-3 w-80 bg-cyber-dark border-2 border-brand p-4 space-y-3 z-20">

    <p class="text-xs text-text-secondary">
        By
        <a href="#" class="text-cyber-cyan hover:text-brand transition-colors">
            {{ $course['user']['name'] }}
        </a>
    </p>

    <h4 class="font-bold text-base text-white leading-6">
        <a href="#" class="hover:text-brand transition-colors">
            {{ $course->course_name }}
        </a>
    </h4>

    <div class="flex flex-wrap items-center gap-2 text-xs">
        <span class="bg-yellow-400 text-black font-bold px-2 py-1 border border-black">
            @if ($course->bestseller == 'yes')
                Bestseller
            @elseif($course->featured == 'yes')
                Featured
            @else
                HighestRated
            @endif
        </span>

        <p class="text-green-400 font-medium">
            Cập nhật
            <span class="font-bold ml-1">
                {{ \Carbon\Carbon::parse($course->updated_at)->format('F Y') }}
            </span>
        </p>
    </div>

    <ul class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-text-secondary">
        <li class="flex items-center">
            <i class="fas fa-clock text-brand mr-1"></i>
            23 total hours
        </li>
        <li class="flex items-center">
            <i class="fas fa-tag text-brand mr-1"></i>
            {{ $course->label }}
        </li>
    </ul>

    <p class="text-sm text-text-secondary leading-6">
        {{ $course->course_title }}
    </p>

    @if (!empty($course['goals']))
        <ul class="text-xs space-y-2 text-text-secondary">
            @foreach ($course['goals']->slice(0, 5) as $goal)
                <li class="flex items-start gap-2">
                    <i class="fas fa-check text-brand mt-1"></i>
                    <span>{{ $goal->goal_name }}</span>
                </li>
            @endforeach
        </ul>
    @endif

    <div class="flex items-center gap-3 pt-2">
        <button type="button"
            class="flex-1 bg-brand text-black px-3 py-2 text-xs font-bold uppercase border border-black pixel-button-hover add-to-cart-btn"
            data-course-id="{{ $course->id }}">
            <i class="fas fa-shopping-cart mr-1"></i>
            Add to cart
        </button>

        {{-- @php
            if (auth()->check()) {
                $user_id = auth()->user()->id;
                $isWishlisted = \App\Models\Wishlist::where('user_id', $user_id)
                    ->where('course_id', $course->id)
                    ->first();
            } else {
                $isWishlisted = null;
            }
        @endphp --}}

        <div class="wishlist-icon w-10 h-10 flex items-center justify-center border border-black bg-cyber-surface cursor-pointer text-red-500"
            title="Add to Wishlist" data-course-id="{{ $course->id }}">
            {{-- @if ($isWishlisted)
                <i class="fas fa-heart"></i>
            @else
                <i class="far fa-heart"></i>
            @endif --}}
        </div>
    </div>
</div>
