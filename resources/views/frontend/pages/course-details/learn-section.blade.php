<section class="bg-cyber-surface p-6 pixel-border border-2 border-black relative">
    <h3 class="mb-4 text-xl font-bold uppercase tracking-tighter text-brand">Bạn sẽ học được gì?</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($course['goals'] as $item)
            <div class="flex items-start gap-3"><i class="fas fa-check text-brand mt-1"></i>
                <p class="text-sm text-slate-300">{{ $item->goal_name }}</p>
            </div>
        @endforeach
    </div>
</section>
<!-- 4.2. Curated for Business -->
<section
    class="bg-gradient-to-r from-pink-500/20 to-cyber-cyan/20 p-6 pixel-border border-2 border-black flex items-center justify-between">
    <div>
        <h4 class="font-bold text-slate-100 mb-1">Curated for the StackLearn for Business collection</h4>
        <p class="text-xs text-slate-400">Upgrade your team with highly rated courses.</p>
    </div>
    <i class="fas fa-briefcase text-3xl text-slate-100 opacity-50"></i>
</section>

<!-- 4.3. Requirements -->
<section class="p-6 border-b-2 border-black bg-black/20">
    <h3 class="mb-4 text-xl font-bold uppercase tracking-tighter text-brand">Yêu cầu</h3>
    <div
        class="text-slate-300 text-sm space-y-2 [&_ol]:list-decimal [&_ol]:pl-5 [&_ul]:list-disc [&_ul]:pl-5 [&_*]:!text-slate-300 [&_*]:!bg-transparent">
        {!! $course['prerequisites'] !!}</div>
</section>

<!-- 4.4. Top companies trust StackLearn -->
<section class="p-6 border-b-2 border-black">
    <h3 class="font-pixel text-xs text-text-secondary mb-4 text-center">Top companies trust StackLearn</h3>
    <div class="flex justify-between items-center opacity-50 text-xl font-bold font-mono">
        <span>Google</span><span>Microsoft</span><span>Amazon</span><span>Netflix</span><span>Meta</span>
    </div>
    <div class="text-center mt-6">
        <button
            class="border-2 border-brand text-brand px-4 py-2 text-xs font-bold uppercase hover:bg-brand hover:text-black transition-colors pixel-button-hover">Try
            StackLearn for Business</button>
    </div>
</section>
