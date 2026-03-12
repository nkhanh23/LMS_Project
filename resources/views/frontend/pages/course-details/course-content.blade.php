<!-- 4.5. Description -->
<section class="p-6 border-b-2 border-black">
    <h3 class="mb-4 text-xl font-bold uppercase tracking-tighter text-brand">Mô tả</h3>
    <div
        class="desc-content text-sm text-slate-300 space-y-3 relative overflow-hidden h-32 [&_ol]:list-decimal [&_ol]:pl-5 [&_ul]:list-disc [&_ul]:pl-5 [&_*]:!text-slate-300 [&_*]:!bg-transparent">
        {!! $course['description'] !!}
    </div>
    <button class="show-more-btn text-cyber-cyan text-sm font-bold uppercase mt-4 hover:underline hidden"
        onclick="const wrapper = this.previousElementSibling; wrapper.classList.toggle('h-32'); this.innerText = this.innerText === 'SHOW MORE' ? 'SHOW LESS' : 'SHOW MORE';">Show
        more</button>

</section>

<section class="p-6 bg-cyber-surface pixel-border border-2 border-black">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xl font-bold uppercase tracking-tighter text-brand">Nội dung bài học</h3>
        <span class="text-xs text-slate-400">{{ $total_lecture }} phần • 15 bài học • 2h 30m total length</span>
    </div>
    <div class="space-y-4">
        @foreach ($course_content as $index => $item)
            <!-- Section 1 -->
            <div class="border border-slate-700 bg-black/40">
                <button
                    class="w-full p-4 flex justify-between items-center text-left hover:bg-slate-800 transition-colors">
                    <span class="font-bold text-sm">Phần {{ $index + 1 }}: {{ $item->section_title }}</span>
                    <span class="text-xs text-slate-400 shrink-0">{{ $item->lecture->count() }} lectures • 10m</span>
                </button>
                <div class="p-4 border-t border-slate-700 space-y-3 bg-cyber-dark text-sm">
                    @foreach ($item->lecture as $lecture)
                        <div class="flex justify-between items-center group">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-play-circle text-cyber-cyan"></i>
                                <a href="{{ route('course.lecture.watch', [$course->course_name_slug, $lecture->id]) }}"
                                    class="hover:text-cyber-cyan group-hover:underline">{{ $lecture->lecture_title }}</a>
                            </div>
                            <span class="text-xs text-slate-500">Preview <span class="ml-2">02:30</span></span>
                        </div>
                    @endforeach
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-file-alt text-brand"></i>
                            <span class="text-slate-300">Course Syllabus (Document)</span>
                        </div>
                        <span class="text-xs text-slate-500">01:00</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<script>
    (function() {
        const checkHeight = function() {
            const wrappers = document.querySelectorAll('.desc-content');
            wrappers.forEach(desc => {
                const btn = desc.nextElementSibling;
                if (btn && btn.classList.contains('show-more-btn')) {
                    if (desc.scrollHeight > desc.clientHeight) {
                        btn.classList.remove('hidden');
                    } else {
                        btn.classList.add('hidden');
                    }
                }
            });
        };

        // Run immediately after parsing
        checkHeight();

        // Also run on load and resize to handle slow fonts/images
        window.addEventListener('load', checkHeight);
        window.addEventListener('resize', checkHeight);

        // For SPA/Livewire navigation
        document.addEventListener('livewire:navigated', checkHeight);
        document.addEventListener('turbolinks:load', checkHeight);
    })();
</script>
