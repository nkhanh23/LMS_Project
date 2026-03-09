<div class="bg-cyber-surface pixel-border border-4 border-black overflow-hidden relative">
    <!-- Video Thumbnail -->
    <div class="aspect-video bg-black relative flex items-center justify-center cursor-pointer group"
        data-bs-toggle="modal" data-bs-target="#previewModal">
        <img src="{{ asset($course->course_image) }}" alt="Video Preview"
            class="w-full h-full object-cover opacity-60 group-hover:opacity-100 transition-opacity">
        <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
            <button
                class="w-16 h-16 bg-white/20 text-white rounded-full flex items-center justify-center group-hover:bg-brand group-hover:text-black transition-colors backdrop-blur-sm">
                <i class="fas fa-play text-2xl ml-1"></i>
            </button>
        </div>
        <span
            class="absolute bottom-4 left-4 text-xs font-bold text-white uppercase tracking-widest bg-black/60 px-2 py-1">Preview
            this course</span>
    </div>
    <!-- Meta & Buttons -->
    <div class="p-6">
        <div class="flex items-end gap-2 mb-6">
            <span class="text-3xl font-black text-brand pixel-text">{{ $course->discount_price }}</span>
            <span class="text-slate-500 line-through text-sm">{{ $course->selling_price }}</span>
            <span class="bg-pink-500 text-white text-[10px] px-2 py-0.5 font-bold animate-pulse">Giảm
                {{ round((($course->selling_price - $course->discount_price) / $course->selling_price) * 100) }}
                %</span>
        </div>
        <div class="space-y-3 mb-4 text-center">
            <button
                class="w-full bg-brand py-3 text-black font-black uppercase tracking-widest text-sm border-2 border-black pixel-shadow pixel-button-hover">Add
                to cart</button>
            <button
                class="w-full bg-card-dark border-2 border-slate-500 py-3 text-white font-bold uppercase tracking-widest text-sm hover:border-white transition-colors">Buy
                this course</button>
        </div>
        <p class="text-center text-xs text-slate-400 mb-6 font-mono">30-Day Money-Back Guarantee</p>
        <!-- This course includes list -->
        <div class="border-t border-slate-700 pt-5">
            <h4 class="font-bold text-sm text-slate-100 mb-3">This course includes:</h4>
            <ul class="space-y-3 text-sm text-slate-300">
                <li class="flex items-center gap-3"><i class="fas fa-video text-secondary w-4 text-center"></i>
                    80 hours on-demand
                    video</li>
                <li class="flex items-center gap-3"><i class="far fa-newspaper text-secondary w-4 text-center"></i> 3
                    articles</li>
                <li class="flex items-center gap-3"><i class="fas fa-file-download text-secondary w-4 text-center"></i>
                    2
                    downloadable
                    resources</li>
                <li class="flex items-center gap-3"><i class="fas fa-code text-secondary w-4 text-center"></i> 1
                    coding exercise
                </li>
                <li class="flex items-center gap-3"><i class="fas fa-infinity text-secondary w-4 text-center"></i> Full
                    lifetime
                    access</li>
                <li class="flex items-center gap-3"><i class="fas fa-mobile-alt text-secondary w-4 text-center"></i>
                    Access on
                    mobile
                    and TV</li>
                <li class="flex items-center gap-3"><i class="fas fa-certificate text-secondary w-4 text-center"></i>
                    Certificate
                    of
                    completion</li>
            </ul>
        </div>
        <!-- Buy for team -->
        <div class="mt-6 bg-black/30 p-4 border border-slate-700 text-center rounded-sm">
            <h4 class="font-bold text-sm text-slate-100 mb-2">Training 5 or more people?</h4>
            <p class="text-xs text-slate-400 mb-3">Get your team access to 8,000+ top StackLearn
                courses anytime, anywhere.</p>
            <button
                class="w-full border-2 border-cyber-cyan text-cyber-cyan font-bold py-2 text-xs uppercase hover:bg-cyber-cyan hover:text-black transition-colors">Try
                StackLearn for Business</button>
        </div>
    </div>
</div>
