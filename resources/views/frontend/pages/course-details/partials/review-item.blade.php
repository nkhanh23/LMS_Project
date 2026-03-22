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

        @auth
            @if (auth()->id() !== $review->user_id)
                <div class="mt-2" id="review-report-wrapper-{{ $review->id }}">
                    <button type="button" class="btn btn-sm btn-outline-danger"
                        onclick="document.getElementById('review-report-form-{{ $review->id }}').classList.toggle('hidden')">
                        <i class="fa-solid fa-flag mr-1"></i> Báo cáo
                    </button>

                    <form id="review-report-form-{{ $review->id }}" class="hidden mt-2 report-review-form"
                        action="{{ route('reports.reviews.store', $review->id) }}" method="POST"
                        data-review-id="{{ $review->id }}">
                        @csrf
                        <div class="mb-2">
                            <select name="reason_code"
                                class="w-full border rounded p-2 mb-2 bg-cyber-dark text-white border-slate-700" required>
                                <option value="">-- Chọn lý do --</option>
                                <option value="spam">Spam</option>
                                <option value="abuse">Abuse</option>
                                <option value="harassment">Harassment</option>
                                <option value="hate_speech">Hate speech</option>
                                <option value="adult">Adult</option>
                                <option value="misinformation">Misinformation</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <textarea name="description"
                                class="w-full border rounded p-2 mb-2 bg-cyber-dark text-white border-slate-700 font-sans"
                                rows="3" placeholder="Mô tả thêm"></textarea>
                        </div>

                        <button type="submit"
                            class="px-3 py-2 bg-red-600 text-white rounded font-bold uppercase text-[10px] tracking-widest pixel-shadow-sm hover:bg-red-700">
                            Gửi report
                        </button>
                    </form>
                </div>
            @endif
        @endauth
    </div>
</div>
