<div>
    <h2 class="text-brand text-3xl lg:text-4xl font-bold pixel-text tracking-tight mb-2 font-pixel">
        Xin chào, @auth {{ auth()->user()->name }}
        @else
        Khách @endauth!
    </h2>
    <p
        class="text-cyber-cyan font-bold text-sm tracking-widest bg-black/30 inline-block px-2 py-1 border border-cyber-cyan/30 font-mono">
        SYSTEM STATUS: ALL SYSTEMS NOMINAL // CREDITS ACQUIRED
    </p>
</div>
