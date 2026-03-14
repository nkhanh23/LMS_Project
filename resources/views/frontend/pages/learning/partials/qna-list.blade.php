<div class="flex items-center justify-between border-b-2 border-slate-800 pb-4">
    <h4 id="discussionCount" class="text-lg font-black text-white uppercase tracking-tighter">
        {{ $discussions->total() }} Câu hỏi & Thảo luận
    </h4>

    <div class="flex gap-4 text-xs font-bold text-slate-500 uppercase">
        <button type="button" class="text-brand">Mới nhất</button>
        <button type="button" class="hover:text-white">Phổ biến</button>
    </div>
</div>
<div id="discussionItems" class="space-y-6" data-course-id="{{ $course->id }}"
    data-lecture-id="{{ $currentLecture->id }}" data-store-url="{{ route('lecture.discussion.store') }}">
    @forelse($discussions as $discussion)
        @include('frontend.pages.learning.partials.discussion-item', [
            'discussion' => $discussion,
            'depth' => 0,
        ])
    @empty
        <p id="emptyDiscussionMessage" class="text-center text-slate-400 uppercase font-bold tracking-widest text-sm">
            <i class="fa-solid fa-triangle-exclamation mr-2"></i> Không có câu hỏi nào
        </p>
    @endforelse
</div>
