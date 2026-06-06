<div class="lg:hidden bg-cyber-dark border-b-2 border-black px-4 py-2">
    <div class="relative" id="mobile-search-wrapper">
        <form action="{{ route('frontend.courses.index') }}" method="GET" id="mobile-search-form" autocomplete="off">
            <div class="bg-black/50 border-2 border-slate-700 focus-within:border-brand p-2 flex items-center gap-2 transition-colors">
                <span class="text-brand font-mono">&gt;</span>
                <input
                    id="mobile-search-input"
                    name="q"
                    value="{{ request('q') }}"
                    class="bg-transparent border-none outline-none focus:ring-0 text-sm w-full placeholder:text-slate-500 text-text-primary"
                    placeholder="Tìm kiếm khóa học..." type="text"
                    autocomplete="off"
                />
                <button type="submit" class="text-brand hover:text-white transition-colors">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
        <!-- Autocomplete Dropdown Mobile -->
        <div id="mobile-search-dropdown"
            class="hidden absolute top-full left-0 right-0 mt-1 bg-cyber-surface border-2 border-brand z-[999] pixel-shadow max-h-80 overflow-y-auto">
        </div>
    </div>
</div>