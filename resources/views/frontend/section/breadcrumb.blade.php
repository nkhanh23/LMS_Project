<div
    class="mb-10 w-full bg-[#282A3A] retro-border shadow-retro relative overflow-hidden p-8 flex flex-col justify-end min-h-[160px]">
    <div
        class="absolute inset-0 pointer-events-none opacity-20 bg-[linear-gradient(rgba(0,229,255,0.1)_1px,transparent_1px),linear-gradient(90deg,rgba(0,229,255,0.1)_1px,transparent_1px)] bg-[size:20px_20px]">
    </div>
    <div class="relative z-10 flex flex-col gap-4">
        <nav class="flex items-center gap-2 text-[#A6ACCD] text-xs md:text-sm font-bold uppercase tracking-widest">
            <a class="hover:text-[#4bf425] transition-colors" href="#">Home</a>
            <span class="text-[#00E5FF] font-black">&gt;</span>
            <a class="hover:text-[#4bf425] transition-colors" href="#">Pages</a>
            <span class="text-[#00E5FF] font-black">&gt;</span>
            <span class="text-[#4bf425]">{{ $title }}</span>
        </nav>
        <h1
            class="text-slate-100 text-4xl md:text-5xl font-black uppercase tracking-widest drop-shadow-[2px_2px_0_rgba(0,0,0,1)]">
            {{ $title }}</h1>
    </div>
</div>
