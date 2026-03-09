<div class="bg-black/30 border border-slate-700 p-6">
    <h4 class="font-bold uppercase tracking-tighter text-brand mb-4 border-b border-slate-700 pb-2">
        Course Categories</h4>
    <div class="flex flex-wrap gap-2 text-xs">
        @foreach ($all_category as $item)
            <a href="#"
                class="px-3 py-1 bg-cyber-dark border border-slate-600 hover:border-brand hover:text-brand transition-colors text-slate-300">{{ $item->name }}</a>
        @endforeach
    </div>
</div>
