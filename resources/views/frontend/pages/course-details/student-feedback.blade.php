<section class="py-6">
    <h3 class="mb-4 text-xl font-bold uppercase tracking-tighter text-brand">Đánh giá khóa học</h3>

    <div class="flex gap-8 items-center border border-slate-700 p-6 bg-black/30">
        <div class="text-center shrink-0">
            <div class="text-5xl font-black text-yellow-400">
                {{ number_format($ratingAverage ?? 0, 1) }}
            </div>

            <div class="text-yellow-400 text-sm mt-2">
                @php
                    $avg = $ratingAverage ?? 0;
                @endphp

                @for ($i = 1; $i <= 5; $i++)
                    @if ($avg >= $i)
                        <i class="fas fa-star"></i>
                    @elseif ($avg >= $i - 0.5)
                        <i class="fas fa-star-half-alt"></i>
                    @else
                        <i class="far fa-star text-slate-600"></i>
                    @endif
                @endfor
            </div>

            <div class="text-xs text-slate-400 mt-1">
                {{ $ratingCount ?? 0 }} {{ ($ratingCount ?? 0) > 1 ? 'đánh giá' : 'đánh giá' }}
            </div>
        </div>

        <div class="flex-1 space-y-2">
            @for ($star = 5; $star >= 1; $star--)
                <div class="flex items-center gap-3">
                    <div class="w-full bg-slate-800 h-2 rounded-full overflow-hidden">
                        <div class="bg-brand h-full" style="width: {{ $ratingPercent[$star] ?? 0 }}%"></div>
                    </div>

                    <div class="text-yellow-400 text-xs shrink-0 w-28 flex justify-end items-center gap-1">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $star)
                                <i class="fas fa-star"></i>
                            @else
                                <i class="far fa-star text-slate-600"></i>
                            @endif
                        @endfor

                        <span class="text-slate-400 ml-1">
                            {{ $ratingPercent[$star] ?? 0 }}%
                        </span>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</section>
