<div id="noteItems" class="space-y-6" data-store-url="{{ route('lecture.notes.store') }}">
    <!-- Mock Note Item 1 -->
    @forelse($notes as $note)
        @include('frontend.pages.learning.partials.note-item', ['note' => $note])
    @empty
        <p id="emptyNoteMessage" class="text-center text-slate-400 uppercase font-bold tracking-widest text-sm">
            Chưa có ghi chú nào
        </p>
    @endforelse
</div>
