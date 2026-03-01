<?php
$categories = getCategories();
?>


<!-- Header Top -->
<div class="bg-black/60 border-b border-cyber-surface text-xs py-2 px-4 hidden md:block">
    <div class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-6 text-text-secondary">
            <span><i class="fas fa-phone text-brand mr-1"></i> +84 123 456 789</span>
            <span><i class="fas fa-envelope text-brand mr-1"></i> contact@stacklearn.dev</span>
        </div>
        <div class="flex items-center gap-4">
            <span class="text-cyber-surface">|</span>
            <a href="#" class="text-text-secondary hover:text-brand transition-colors"><i
                    class="fas fa-sign-in-alt mr-1"></i>Login</a>
            <a href="#" class="text-brand font-bold hover:text-white transition-colors"><i
                    class="fas fa-user-plus mr-1"></i>Register</a>
        </div>
    </div>
</div>

<!-- Main Header -->
<header class="sticky top-0 z-50 bg-cyber-dark border-b-4 border-black px-4 lg:px-6">
    <div class="max-w-7xl mx-auto flex items-center justify-between h-16 lg:h-20">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-2 shrink-0">
            <div class="flex flex-col gap-0.5">
                <div class="w-3 h-3 bg-brand pixel-border"></div>
                <div class="w-3 h-3 bg-cyber-cyan pixel-border"></div>
                <div class="w-3 h-3 bg-pink-500 pixel-border"></div>
            </div>
            <span class="text-xl lg:text-2xl font-bold tracking-tighter uppercase italic">StackLearn</span>
        </a>

        <!-- Category Dropdown + Search (Desktop) -->
        <div class="hidden lg:flex items-center gap-2 flex-1 max-w-2xl mx-6">
            <!-- Category Button -->
            <div class="relative group/cat">
                <button
                    class="bg-cyber-surface border-2 border-black px-4 py-2 text-sm font-bold flex items-center gap-2 hover:bg-brand hover:text-black transition-colors">
                    <i class="fas fa-th"></i> Danh mục <i class="fas fa-chevron-down text-[10px]"></i>
                </button>
                <div class="absolute top-full left-0 pt-1 w-64 z-50 hidden group-hover/cat:block">
                    <div class="bg-cyber-surface border-2 border-black pixel-shadow">
                        @foreach ($categories as $item)
                            <div class="relative group/item">
                                <a href=""
                                    class="block px-4 py-3 hover:bg-brand hover:text-black border-b border-black/30 flex items-center justify-between">
                                    <span class="flex items-center gap-2"><i
                                            class="fas fa-briefcase text-cyber-cyan group-hover/item:text-black"></i>
                                        {{ $item->name }}</span>
                                    @if ($item->subCategory->count() > 0)
                                        <i class="fas fa-chevron-right text-[10px] text-text-secondary"></i>
                                    @endif
                                </a>
                                @if ($item->subCategory->count() > 0)
                                    <div class="absolute top-0 left-full pl-1 w-56 hidden group-hover/item:block z-50">
                                        <div class="bg-cyber-surface border-2 border-black pixel-shadow">
                                            @foreach ($item->subCategory as $sub)
                                                <a href=""
                                                    class="block px-4 py-3 hover:bg-brand hover:text-black border-b border-black/30 text-sm">
                                                    {{ $sub->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- Terminal Search -->
            <div class="flex-1 bg-black/50 border-2 border-slate-700 p-2 flex items-center gap-2">
                <span class="text-brand font-mono">&gt;</span>
                <input
                    class="bg-transparent border-none outline-none focus:ring-0 text-sm w-full placeholder:text-slate-500 text-text-primary"
                    placeholder="Search for quests..." type="text" />
                <div class="w-[2px] h-5 bg-brand cursor-blink border-r-2"></div>
            </div>
        </div>

        <!-- Desktop Nav -->
        <nav class="hidden lg:flex items-center gap-5 text-sm font-bold uppercase">
            <a href="#" class="text-brand">Home</a>
            <a href="#" class="hover:text-brand transition-colors">Stacks</a>
            <a href="#" class="hover:text-brand transition-colors">Devs</a>
            <a href="#" class="hover:text-brand transition-colors">Pages</a>
            <a href="#" class="hover:text-brand transition-colors">Blog</a>
        </nav>

        <!-- Right Actions -->
        <div class="flex items-center gap-3 lg:gap-5 ml-4">
            <!-- Cart -->
            <div class="relative cart-trigger cursor-pointer">
                <i class="fas fa-shopping-cart text-lg hover:text-brand transition-colors"></i>
                <span
                    class="absolute -top-2 -right-3 bg-red-600 text-white text-[9px] font-bold px-1.5 py-0.5 pixel-border">2</span>
                <!-- Cart Dropdown -->
                <div
                    class="cart-dropdown absolute top-full right-0 mt-3 w-72 bg-cyber-surface border-2 border-black pixel-shadow z-50">
                    <div class="p-3 border-b border-black/30">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-12 h-12 bg-cyber-dark border border-black"></div>
                            <div class="flex-1">
                                <p class="text-sm font-bold truncate">Laravel Masterclass</p>
                                <p class="text-brand text-sm font-bold">$49.99</p>
                            </div>
                            <button class="text-red-500 hover:text-red-400"><i class="fas fa-times"></i></button>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-cyber-dark border border-black"></div>
                            <div class="flex-1">
                                <p class="text-sm font-bold truncate">Design Patterns</p>
                                <p class="text-brand text-sm font-bold">$54.99</p>
                            </div>
                            <button class="text-red-500 hover:text-red-400"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                    <div class="p-3 flex justify-between items-center">
                        <span class="font-bold">Total: <span class="text-brand">$104.98</span></span>
                        <a href="#"
                            class="bg-brand text-black px-4 py-2 text-xs font-bold uppercase pixel-border">Checkout</a>
                    </div>
                </div>
            </div>
            <!-- Theme Toggle (mobile) -->
            <button id="themeToggleMobile" class="hover:text-brand transition-colors sm:hidden">
                <i class="fas fa-moon"></i>
            </button>
            <!-- Sign In -->
            <a href="#"
                class="hidden sm:block bg-brand text-black font-bold py-2 px-5 pixel-border pixel-shadow pixel-button-hover text-xs uppercase">Sign
                In</a>
            <!-- Mobile Hamburger -->
            <button id="mobileMenuBtn" class="lg:hidden hover:text-brand transition-colors">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </div>
</header>
