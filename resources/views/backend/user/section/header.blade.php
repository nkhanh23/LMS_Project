<header class="h-20 border-b-4 border-black bg-cyber-dark px-8 flex items-center justify-between sticky top-0 z-20">
    <!-- Search -->
    <div class="flex-1 max-w-xl">
        <div class="bg-black/50 border-2 border-slate-700 p-2 flex items-center gap-2">
            <span class="text-brand font-mono">&gt;</span>
            <input
                class="bg-transparent border-none outline-none focus:ring-0 text-sm w-full placeholder:text-slate-500 text-text-primary"
                placeholder="Search your courses..." type="text" />
            <div class="w-[2px] h-5 bg-brand cursor-blink border-r-2"></div>
        </div>
    </div>
    <!-- Right Actions -->
    <div class="flex items-center gap-5 ml-8">

        <!-- ===== NOTIFICATIONS ===== -->
        <div class="relative group/notif">
            <button
                class="relative w-12 h-12 bg-cyber-surface border-2 border-black pixel-shadow-sm pixel-button-hover flex items-center justify-center">
                <i class="fas fa-bell"></i>
                <div class="absolute top-1 right-1 w-3 h-3 bg-red-600 border border-black"></div>
            </button>
            <!-- Notifications Dropdown -->
            <div class="absolute top-full right-0 pt-2 w-80 hidden group-hover/notif:block z-50">
                <div class="bg-cyber-surface border-2 border-black pixel-shadow">
                    <div class="px-4 py-3 border-b-2 border-black flex items-center justify-between">
                        <h4 class="font-bold text-sm pixel-text text-brand">Notifications</h4>
                        <span class="text-[10px] bg-red-600 text-white px-2 py-0.5 border border-black font-bold">3
                            NEW</span>
                    </div>
                    <div class="max-h-72 overflow-y-auto">
                        <a href="#"
                            class="flex items-start gap-3 px-4 py-3 border-b border-black/30 hover:bg-white/5 transition-colors">
                            <div
                                class="w-8 h-8 bg-brand/20 border border-brand flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fas fa-trophy text-brand text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold">Achievement Unlocked!</p>
                                <p class="text-xs text-text-secondary">You completed "Laravel Basics" quest</p>
                                <span class="text-[10px] text-text-secondary mt-1 block">2 hours ago</span>
                            </div>
                        </a>
                        <a href="#"
                            class="flex items-start gap-3 px-4 py-3 border-b border-black/30 hover:bg-white/5 transition-colors">
                            <div
                                class="w-8 h-8 bg-cyber-cyan/20 border border-cyber-cyan flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fas fa-comment text-cyber-cyan text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold">New reply on your question</p>
                                <p class="text-xs text-text-secondary">Instructor responded to your thread</p>
                                <span class="text-[10px] text-text-secondary mt-1 block">5 hours ago</span>
                            </div>
                        </a>
                        <a href="#"
                            class="flex items-start gap-3 px-4 py-3 border-b border-black/30 hover:bg-white/5 transition-colors">
                            <div
                                class="w-8 h-8 bg-pink-500/20 border border-pink-500 flex items-center justify-center shrink-0 mt-0.5">
                                <i class="fas fa-tag text-pink-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-sm font-bold">Flash Sale: 50% Off!</p>
                                <p class="text-xs text-text-secondary">Limited time offer on all courses</p>
                                <span class="text-[10px] text-text-secondary mt-1 block">1 day ago</span>
                            </div>
                        </a>
                    </div>
                    <a href="#"
                        class="block px-4 py-3 text-center text-xs font-bold text-brand pixel-text hover:bg-white/5 transition-colors border-t-2 border-black">
                        VIEW ALL NOTIFICATIONS
                    </a>
                </div>
            </div>
        </div>

        <!-- ===== CART ===== -->
        <div id="cart">
            @include('backend.user.section.partials.cart')
        </div>

        <!-- ===== WISHLIST ===== -->
        <div id="wishlist-course">
            @include('backend.user.section.partials.wishlist')
        </div>


        <!-- ===== AVATAR / USER MENU ===== -->
        <div class="relative group/avatar">
            <button
                class="w-12 h-12 bg-brand border-4 border-black pixel-shadow-sm overflow-hidden flex items-center justify-center hover:brightness-110 transition-all">
                @if (auth()->user()->photo)
                    <img src="{{ asset(auth()->user()->photo) }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-user-astronaut text-black text-xl"></i>
                @endif
            </button>
            <!-- User Menu Dropdown -->
            <div class="absolute top-full right-0 pt-2 w-56 hidden group-hover/avatar:block z-50">
                <div class="bg-cyber-surface border-2 border-black pixel-shadow">
                    <!-- User Info -->
                    <div class="px-4 py-3 border-b-2 border-black flex items-center gap-3">
                        <div class="w-10 h-10 bg-brand border-2 border-black flex items-center justify-center shrink-0">
                            <i class="fas fa-user text-black"></i>
                        </div>
                        <div class="overflow-hidden">
                            <p class="text-sm font-bold truncate">
                                @auth {{ auth()->user()->name }}
                                @else
                                Khách @endauth
                            </p>
                            <p class="text-[10px] text-brand pixel-text font-pixel">Level 42</p>
                        </div>
                    </div>
                    <!-- Menu Items -->
                    <div class="py-1">
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/5 transition-colors text-sm">
                            <i class="fas fa-play-circle text-cyber-cyan w-4 text-center"></i>
                            <span>Khóa học của tôi</span>
                        </a>
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/5 transition-colors text-sm">
                            <i class="fas fa-shopping-basket text-yellow-400 w-4 text-center"></i>
                            <span>Giỏ hàng</span>
                        </a>
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/5 transition-colors text-sm">
                            <i class="fas fa-heart text-pink-400 w-4 text-center"></i>
                            <span>Danh sách yêu thích</span>
                        </a>
                        <div class="border-t border-black/30 my-1"></div>
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/5 transition-colors text-sm">
                            <i class="fas fa-bell text-brand w-4 text-center"></i>
                            <span>Thông báo</span>
                            <span
                                class="ml-auto bg-cyber-cyan/20 text-cyber-cyan text-[10px] px-1.5 py-0.5 font-bold border border-cyber-cyan/30">9+</span>
                        </a>
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/5 transition-colors text-sm">
                            <i class="fas fa-envelope text-cyber-cyan w-4 text-center"></i>
                            <span>Tin nhắn</span>
                            <span
                                class="ml-auto bg-cyber-cyan/20 text-cyber-cyan text-[10px] px-1.5 py-0.5 font-bold border border-cyber-cyan/30">12+</span>
                        </a>
                        <div class="border-t border-black/30 my-1"></div>
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/5 transition-colors text-sm">
                            <i class="fas fa-cog text-text-secondary w-4 text-center"></i>
                            <span>Cài đặt</span>
                        </a>
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/5 transition-colors text-sm">
                            <i class="fas fa-history text-text-secondary w-4 text-center"></i>
                            <span>Lịch sử thanh toán</span>
                        </a>
                        <div class="border-t border-black/30 my-1"></div>
                        <a href="{{ route('user.profile') }}"
                            class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/5 transition-colors text-sm">
                            <i class="fas fa-user-circle text-text-secondary w-4 text-center"></i>
                            <span>Thông tin cá nhân</span>
                        </a>
                        <a href="{{ route('user.profile.edit') }}"
                            class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/5 transition-colors text-sm">
                            <i class="fas fa-edit text-text-secondary w-4 text-center"></i>
                            <span>Chỉnh sửa thông tin</span>
                        </a>
                        <div class="border-t border-black/30 my-1"></div>
                        <a href="#"
                            class="flex items-center gap-3 px-4 py-2.5 hover:bg-white/5 transition-colors text-sm">
                            <i class="fas fa-question-circle text-text-secondary w-4 text-center"></i>
                            <span>Trợ giúp</span>
                        </a>
                        <form method="POST" action="{{ route('user.logout') }}">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-red-500/20 transition-colors text-sm text-red-400">
                                <i class="fas fa-power-off w-4 text-center"></i>
                                <span>Đăng xuất</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</header>
