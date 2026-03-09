<section class="p-6 bg-cyber-surface border-2 border-black pixel-border">
    <h3 class="mb-6 text-xl font-bold uppercase tracking-tighter text-brand">About the instructor</h3>
    <div class="flex flex-col md:flex-row gap-6">
        <div class="shrink-0">
            <div class="w-32 h-32 bg-slate-800 border-2 border-slate-600 overflow-hidden">
                <img loading="lazy" src="{{ $course['user']['photo'] }}" alt="{{ $course['user']['name'] }}"
                    class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all">
            </div>
        </div>
        <div>
            <h4 class="font-bold text-lg text-cyber-cyan hover:underline cursor-pointer">{{ $course['user']['name'] }}
            </h4>
            <p class="text-xs text-slate-400 mb-3">{{ $course['user']['experience'] }}</p>
            <div class="flex items-center gap-4 text-sm text-slate-300 mb-4">
                <span class="flex items-center gap-1"><i class="fas fa-star text-yellow-400"></i> 4.5
                    Rating</span>
                <span class="flex items-center gap-1"><i class="fas fa-user text-brand"></i> 1.2M
                    Students</span>
                <span class="flex items-center gap-1"><i class="fas fa-play-circle text-pink-400"></i>
                    12
                    Courses</span>
            </div>
            <p class="text-sm text-slate-400">{{ $course['user']['bio'] }}</p>
        </div>
    </div>
</section>
