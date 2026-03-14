@php
    $depth = $depth ?? 0;
@endphp

<div id="discussion-item-{{ $discussion->id }}"
    class="discussion-node border-b border-slate-800 py-4 {{ $depth > 0 ? 'ml-6 mt-3' : '' }}">

    <div class="flex gap-4">
        <div class="size-10 bg-slate-700 border-2 border-brand overflow-hidden shrink-0">
            <img src="{{ $discussion->user->photo ?? 'https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($discussion->user->name) ?>' }}' }}"
                alt="{{ $discussion->user->name }}" class="w-full h-full object-cover">
        </div>

        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-white font-bold">{{ $discussion->user->name }}</span>
                @if (isset($course) && $course->instructor_id === $discussion->user_id)
                    <span
                        class="bg-brand/10 text-brand text-[8px] px-1.5 py-0.5 border border-brand/30 uppercase font-black tracking-tighter">
                        Tác giả
                    </span>
                @endif
                <span class="text-[10px] text-slate-500 uppercase">
                    {{ $discussion->created_at->diffForHumans() }}
                </span>
            </div>

            <p class="text-slate-300 text-sm leading-relaxed mb-3 whitespace-pre-line">
                {{ $discussion->content }}
            </p>

            <div class="flex items-center gap-4 text-xs font-bold text-slate-500 mb-3">
                @auth
                    <button type="button" class="discussion-reply-toggle hover:text-cyber-cyan flex items-center gap-1"
                        data-discussion-id="{{ $discussion->id }}">
                        <i class="fa-regular fa-comment"></i> Trả lời
                    </button>
                @endauth

                @auth
                    @if (auth()->id() === $discussion->user_id)
                        <button type="button" class="discussion-delete-btn hover:text-red-400 flex items-center gap-1"
                            data-discussion-id="{{ $discussion->id }}"
                            data-delete-url="{{ route('lecture.discussion.destroy', $discussion->id) }}">
                            <i class="fa-regular fa-trash-can"></i> Xóa
                        </button>
                    @endif
                @endauth
            </div>

            <div id="reply-form-container-{{ $discussion->id }}" class="hidden mb-3"></div>

            <div id="discussion-children-{{ $discussion->id }}" class="space-y-3">
                @foreach ($discussion->replies as $reply)
                    @include('frontend.pages.learning.partials.discussion-item', [
                        'discussion' => $reply,
                        'depth' => $depth + 1,
                    ])
                @endforeach
            </div>
        </div>
    </div>
</div>
