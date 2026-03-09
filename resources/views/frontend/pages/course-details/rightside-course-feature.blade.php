<div class="bg-black/30 border border-slate-700 p-6">
    <h4 class="font-bold uppercase tracking-tighter text-brand mb-4 border-b border-slate-700 pb-2">
        Course Features</h4>
    <ul class="space-y-3 text-sm text-slate-300">
        <li
            class="flex justify-between items-center relative pl-4 before:content-[''] before:absolute before:left-0 before:top-2 before:w-1.5 before:h-1.5 before:bg-brand">
            <span>Thời lượng</span> <span class="text-slate-500 font-mono">{{ $total_lecture_duration }}</span>
        </li>
        <li
            class="flex justify-between items-center relative pl-4 before:content-[''] before:absolute before:left-0 before:top-2 before:w-1.5 before:h-1.5 before:bg-brand">
            <span>Số bài giảng</span> <span class="text-slate-500 font-mono">{{ $total_lecture }}</span>
        </li>
        <li
            class="flex justify-between items-center relative pl-4 before:content-[''] before:absolute before:left-0 before:top-2 before:w-1.5 before:h-1.5 before:bg-brand">
            <span>Tài nguyên</span> <span class="text-slate-500 font-mono">{{ $course->resources }}</span>
        </li>
        <li
            class="flex justify-between items-center relative pl-4 before:content-[''] before:absolute before:left-0 before:top-2 before:w-1.5 before:h-1.5 before:bg-brand">
            <span>Bài tập</span> <span class="text-slate-500 font-mono">3</span>
        </li>
        <li
            class="flex justify-between items-center relative pl-4 before:content-[''] before:absolute before:left-0 before:top-2 before:w-1.5 before:h-1.5 before:bg-brand">
            <span>Cấp độ</span> <span class="text-slate-500 font-mono">{{ $course->label }}</span>
        </li>
        <li
            class="flex justify-between items-center relative pl-4 before:content-[''] before:absolute before:left-0 before:top-2 before:w-1.5 before:h-1.5 before:bg-brand">
            <span>Chứng chỉ</span> <span
                class="text-slate-500 font-mono">{{ $course->certificate == 'yes' ? 'Có' : 'Không' }}</span>
        </li>
        <li
            class="flex justify-between items-center relative pl-4 before:content-[''] before:absolute before:left-0 before:top-2 before:w-1.5 before:h-1.5 before:bg-brand">
            <span>Ngôn ngữ</span> <span class="text-slate-500 font-mono">Tiếng Anh</span>
        </li>
    </ul>
</div>
