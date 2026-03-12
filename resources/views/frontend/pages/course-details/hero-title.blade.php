<section class="flex flex-col gap-4">
    <div class="flex gap-2 mb-2">
        <span
            class="bg-primary/20 text-primary px-2 py-1 text-xs font-bold border border-primary/40 bestseller-ribbon">{{ $course['label'] }}</span>
    </div>
    <h2 class="text-3xl md:text-5xl font-black text-primary leading-none pixel-text uppercase italic">
        {{ $course->course_name }}
    </h2>
    <p class="text-slate-300 font-mono text-sm">{{ $course->course_title }}
    </p>
    <div class="flex flex-wrap items-center gap-6">
        <div class="flex items-center gap-1 text-yellow-400 rating-block">
            <span class="font-bold text-lg mr-1 rating-number">{{ number_format($ratingAverage ?? 0, 1) }}</span>
            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                class="fas fa-star-half-alt"></i>
            <span class="ml-2 text-sm text-slate-100 total-ratings">({{ $ratingCount ?? 0 }} đánh giá)</span>
            <span class="ml-2 text-sm text-slate-100 total-students">50 học viên</span>
        </div>
        <div class="flex items-center gap-2 instructor-line">
            <span class="text-sm">Tạo bởi <a href="#"
                    class="font-bold text-cyber-cyan uppercase hover:underline">{{ $course['user']['name'] }}</a></span>
        </div>
    </div>
    <div class="flex flex-wrap items-center gap-4 text-xs text-slate-400 mt-2">
        <span class="flex items-center gap-1"><i class="fas fa-exclamation-circle text-secondary"></i>
            Cập nhật lần cuối {{ \Carbon\Carbon::parse($course->updated_at)->format('D M Y') }}</span>
        updated {{ \Carbon\Carbon::parse($course->updated_at)->format('D M Y') }}</span>
        <span class="flex items-center gap-1"><i class="fas fa-globe text-secondary"></i> English</span>
        <div class="flex items-center gap-3 ml-auto">
            <button class="flex items-center gap-1 hover:text-white transition-colors wishlist-btn"><i
                    class="far fa-heart"></i> Wishlist</button>
            <button class="flex items-center gap-1 hover:text-white transition-colors share-btn" data-bs-toggle="modal"
                data-bs-target="#shareModal"><i class="fas fa-share"></i>
                Chia sẻ</button>
            <button class="flex items-center gap-1 hover:text-white transition-colors report-btn" data-bs-toggle="modal"
                data-bs-target="#reportModal"><i class="fas fa-flag"></i> Báo cáo
                vi phạm</button>
        </div>
    </div>
</section>
