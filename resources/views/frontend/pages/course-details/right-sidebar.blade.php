<div class="lg:col-span-4 sidebar sidebar-negative mt-8 lg:mt-0">
    <div class="sticky top-24 flex flex-col gap-6">
        <!-- 5.1 Preview / purchase card -->
        @include('frontend.pages.course-details.rightside-course-preview')

        <!-- 5.2 Course Features -->
        @include('frontend.pages.course-details.rightside-course-feature')

        <!-- 5.3 Course Categories -->
        @include('frontend.pages.course-details.rightside-course-category')

        <!-- 5.4 Related Courses -->
        @include('frontend.pages.course-details.rightside-related-course')

        <!-- 5.5 Course Tags -->
        <div class="bg-black/30 border border-slate-700 p-6">
            <h4 class="font-bold uppercase tracking-tighter text-brand mb-4 border-b border-slate-700 pb-2">
                Tags</h4>
            <div class="flex flex-wrap gap-2 text-xs">
                <a href="#"
                    class="px-2 py-1 bg-slate-800 hover:bg-brand hover:text-black transition-colors text-slate-300">Java</a>
                <a href="#"
                    class="px-2 py-1 bg-slate-800 hover:bg-brand hover:text-black transition-colors text-slate-300">Programming</a>
                <a href="#"
                    class="px-2 py-1 bg-slate-800 hover:bg-brand hover:text-black transition-colors text-slate-300">Backend</a>
            </div>
        </div>
    </div>
</div>
