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
        @if (!auth()->user())
            <div class="flex items-center gap-4">
                <span class="text-cyber-surface">|</span>
                <a href="{{ route('login') }}" class="text-text-secondary hover:text-brand transition-colors"><i
                        class="fas fa-sign-in-alt mr-1"></i>Login</a>
                <a href="{{ route('register') }}" class="text-brand font-bold hover:text-white transition-colors"><i
                        class="fas fa-user-plus mr-1"></i>Register</a>
            </div>
        @else
            <div class="flex items-center gap-4">
                @if (auth()->user()->role == 'user')
                    <a href="{{ route('user.dashboard') }}"
                        class="text-text-secondary hover:text-brand transition-colors"><i
                            class="fas fa-sign-in-alt mr-1"></i>Trang chủ</a>
                @endif
                @if (auth()->user()->role == 'instructor')
                    <a href="{{ route('instructor.dashboard') }}"
                        class="text-text-secondary hover:text-brand transition-colors"><i
                            class="fas fa-sign-in-alt mr-1"></i>Trang chủ</a>
                @endif
                @if (auth()->user()->role == 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                        class="text-text-secondary hover:text-brand transition-colors"><i
                            class="fas fa-sign-in-alt mr-1"></i>Trang chủ</a>
                @endif
                <a href="{{ route('logout') }}" class="text-text-secondary hover:text-brand transition-colors"><i
                        class="fas fa-sign-in-alt mr-1"></i>Đăng xuất</a>
            </div>
        @endif
    </div>
</div>

<!-- Main Header -->
<header class="sticky top-0 z-50 bg-cyber-dark border-b-4 border-black px-4 lg:px-6">
    <div class="max-w-7xl mx-auto flex items-center justify-between h-16 lg:h-20">
        <!-- Logo -->
        <a href="{{ route('frontend.home') }}" class="flex items-center gap-2 shrink-0">
            <div class="flex flex-col gap-0.5">
                <div class="w-3 h-3 bg-brand pixel-border"></div>
                <div class="w-3 h-3 bg-cyber-cyan pixel-border"></div>
                <div class="w-3 h-3 bg-pink-500 pixel-border"></div>
            </div>
            <span href="{{ route('frontend.home') }}"
                class="text-xl lg:text-2xl font-bold tracking-tighter uppercase italic">StackLearn</span>
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
            <a href="#" class="hover:text-brand transition-colors">Khóa học</a>
            <a href="{{ route('cart') }}" class="hover:text-brand transition-colors">Giỏ hàng</a>
            <a href="#" class="hover:text-brand transition-colors">Blog</a>
        </nav>

        <!-- Right Actions -->
        <div class="flex items-center gap-3 lg:gap-5 ml-4">
            <!-- Wishlist -->
            <?php
            if (auth()->check()) {
                $user_id = auth()->user()->id;
                $wishlist = getWishlist();
                $wishlist_count = \App\Models\Wishlist::where('user_id', $user_id)->count();
            } else {
                //trường hợp người dùng chưa đăng nhập
                $wishlist = collect();
                $wishlist_count = 0;
            }
            ?>
            <div class="relative wishlist-trigger cursor-pointer py-4 flex items-center">
                <div class="relative">
                    <i class="fas fa-heart text-lg hover:text-brand transition-colors"></i>
                    <span id="wishlist-count"
                        class="absolute -top-2 -right-3 bg-red-600 text-white text-[9px] font-bold px-1.5 py-0.5 pixel-border">{{ $wishlist_count ?? 0 }}</span>
                </div>
                <!-- Wishlist Dropdown -->
                <div class="wishlist absolute top-[100%] right-0 w-72 z-50">
                    <!-- Transparent padding bridge -->
                    <div class="h-4 w-full bg-transparent"></div>
                    <div class="bg-cyber-surface border-2 border-black pixel-shadow">

                        <div id="wishlist-course">
                            @include('frontend.pages.home.partials.wishlist', [
                                'wishlistItems' => $wishlist,
                                'wishlist_count' => $wishlist_count,
                            ])
                        </div>
                    </div>
                </div>
            </div>
            <!-- Cart -->
            <div class="relative cart-trigger cursor-pointer py-4 flex items-center">
                <div class="relative">
                    <i class="fas fa-shopping-cart text-lg hover:text-brand transition-colors"></i>
                    <span id="cart-count"
                        class="absolute -top-2 -right-3 bg-brand text-black text-[9px] font-bold px-1.5 py-0.5 pixel-border">{{ getCartItems()->count() }}</span>
                </div>
                <!-- Cart Dropdown -->
                <div class="cart-dropdown absolute top-[100%] right-0 w-72 z-50">
                    <!-- Transparent padding bridge -->
                    <div class="h-4 w-full bg-transparent"></div>
                    <div class="bg-cyber-surface border-2 border-black pixel-shadow">
                        <div id="cart">
                            <!-- Cart items loaded via AJAX -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile Hamburger -->
            <button id="mobileMenuBtn" class="lg:hidden hover:text-brand transition-colors">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    </div>
</header>
