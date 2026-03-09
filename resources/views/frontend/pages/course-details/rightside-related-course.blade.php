<div class="bg-black/30 border border-slate-700 p-6">
    <h4 class="font-bold uppercase tracking-tighter text-brand mb-4 border-b border-slate-700 pb-2">
        Related Courses</h4>
    <div class="space-y-4">
        @php
            $related_course = \App\Models\Course::where('subcategory_id', $course->subcategory_id)
                ->where('id', '!=', $course->id)
                ->take(6)
                ->get();
        @endphp
        @foreach ($related_course as $course)
            <div class="flex items-center gap-3 p-2 hover:bg-slate-800 transition-colors group">
                <div class="w-16 h-12 bg-black shrink-0 relative flex items-center justify-center">
                    <img src="{{ asset($course->course_image) }}" alt="{{ $course->course_name }}"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent"></div>
                </div>
                <div class="flex-1 min-w-0">
                    <h5 class="text-xs font-bold text-slate-200 truncate group-hover:text-brand">
                        {{ \Illuminate\Support\Str::limit($course->course_name, 50) }}
                    </h5>
                    <span class="text-brand font-bold text-[10px]">VNĐ
                        {{ number_format($course->selling_price, 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach
    </div>
    <button
        class="w-full mt-4 text-xs font-bold text-slate-400 uppercase tracking-widest hover:text-white transition-colors border border-slate-700 p-2 text-center">View
        All Courses</button>
</div>
