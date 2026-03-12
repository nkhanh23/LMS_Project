<div class="border border-slate-700 bg-cyber-surface p-4 flex gap-4">
    <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center font-bold text-white shrink-0">
        {{ strtoupper(substr($review->user->name ?? 'User', 0, 2)) }}
    </div>
    <div>
        <h4 class="font-bold text-sm text-slate-100">{{ $review->user->name ?? 'User' }}</h4>
        <div class="flex items-center gap-2 text-xs text-slate-400 mb-2">
            <div class="text-yellow-400">
                @for ($i = 1; $i <= 5; $i++)
                    <i class="{{ $i <= $review->rating ? 'fas' : 'far' }} fa-star"></i>
                @endfor
            </div>
            <span>{{ $review->created_at->diffForHumans() }}</span>
        </div>
        <p class="text-sm text-slate-300">{{ $review->comment }}</p>
    </div>
</div>
