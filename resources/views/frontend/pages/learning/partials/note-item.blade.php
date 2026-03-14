<div id="note-item-{{ $note->id }}" class="note-item border-b border-slate-800 py-4">
    <div class="flex gap-4 items-start">
        <!-- Seek Button -->
        <div class="shrink-0 pt-0.5">
            <button type="button"
                class="note-seek-btn bg-cyber-dark text-brand font-black px-2 py-1 border-2 border-brand hover:bg-brand hover:text-black transition-all rounded text-xs min-w-[60px]"
                data-second="{{ $note->video_second }}" title="Nhảy đến {{ $note->formatted_time }}">
                <i class="fa-solid fa-play text-[10px] mr-1"></i>{{ $note->formatted_time }}
            </button>
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1 justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-white font-bold">{{ $note->user->name }}</span>
                    <span class="text-[10px] text-slate-500 uppercase">
                        {{ $note->created_at->diffForHumans() }}
                    </span>
                </div>
                
                <div class="flex items-center gap-3">
                    <button type="button" class="note-edit-btn text-slate-500 hover:text-white transition-colors" 
                        data-id="{{ $note->id }}" data-note="{{ $note->note }}" title="Sửa">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button type="button" class="note-delete-btn text-slate-500 hover:text-red-400 transition-colors" 
                        data-id="{{ $note->id }}" title="Xóa">
                        <i class="fa-regular fa-trash-can"></i>
                    </button>
                </div>
            </div>

            <p class="text-slate-300 text-sm leading-relaxed whitespace-pre-line">
                {{ $note->note }}
            </p>
        </div>
    </div>
</div>
