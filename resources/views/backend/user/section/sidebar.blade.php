<aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-cyber-surface border-r-4 border-black flex flex-col shrink-0 transition-transform duration-300 -translate-x-full lg:translate-x-0 lg:static">
    <!-- Logo -->
    <div class="p-6 border-b-4 border-black">
        <a href="{{ route('frontend.home') }}" class="flex items-center gap-2">
            <div class="flex flex-col gap-0.5">
                <div class="w-3 h-3 bg-brand pixel-border"></div>
                <div class="w-3 h-3 bg-cyber-cyan pixel-border"></div>
                <div class="w-3 h-3 bg-pink-500 pixel-border"></div>
            </div>
            <span class="text-xl font-bold tracking-tighter uppercase italic">StackLearn</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4 flex flex-col gap-2 overflow-y-auto">
        <a class="sidebar-active flex items-center gap-3 px-4 py-3 border-2 border-black font-bold pixel-text text-sm"
            href="{{ route('user.dashboard') }}">
            <i class="fas fa-th-large"></i>
            <span>Trang chủ</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 border-2 border-transparent hover:border-black hover:bg-white/10 transition-colors font-bold pixel-text text-sm"
            href="{{ route('user.profile') }}">
            <i class="fas fa-user text-cyber-cyan"></i>
            <span>Thông tin cá nhân</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 border-2 border-transparent hover:border-black hover:bg-white/10 transition-colors font-bold pixel-text text-sm"
            href="{{ route('user.my-courses') }}">
            <i class="fas fa-book text-cyber-cyan"></i>
            <span>Khóa học của tôi</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 border-2 border-transparent hover:border-black hover:bg-white/10 transition-colors font-bold pixel-text text-sm"
            href="#">
            <i class="fas fa-chart-bar text-pink-400"></i>
            <span>Thống kê</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 border-2 border-transparent hover:border-black hover:bg-white/10 transition-colors font-bold pixel-text text-sm"
            href="{{ route('user.wishlist.index') }}">
            <i class="fas fa-bookmark text-yellow-400"></i>
            <span>Danh sách yêu thích</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 border-2 border-transparent hover:border-black hover:bg-white/10 transition-colors font-bold pixel-text text-sm"
            href="{{ route('user.continue-learning') }}">
            <i class="fas fa-play-circle text-brand"></i>
            <span>Tiếp tục học</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 border-2 border-transparent hover:border-black hover:bg-white/10 transition-colors font-bold pixel-text text-sm"
            href="{{ route('user.quiz-history') }}">
            <i class="fas fa-clipboard-check text-cyber-cyan"></i>
            <span>Lịch sử quiz</span>
        </a>

        <a class="flex items-center gap-3 px-4 py-3 border-2 border-transparent hover:border-black hover:bg-white/10 transition-colors font-bold pixel-text text-sm"
            href="{{ route('user.ai-tutor.history') }}">
            <i class="fas fa-robot text-pink-400"></i>
            <span>AI Tutor</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 border-2 border-transparent hover:border-black hover:bg-white/10 transition-colors font-bold pixel-text text-sm"
            href="{{ route('user.certificates') }}">
            <i class="fas fa-certificate text-brand"></i>
            <span>Chứng chỉ</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 border-2 border-transparent hover:border-black hover:bg-white/10 transition-colors font-bold pixel-text text-sm"
            href="{{ route('user.orders.index') }}">
            <i class="fas fa-money-bill text-brand"></i>
            <span>Lịch sử thanh toán</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 border-2 border-transparent hover:border-black hover:bg-white/10 transition-colors font-bold pixel-text text-sm"
            href="{{ route('user.chat.index') }}">
            <i class="fas fa-comment-dots text-cyber-cyan"></i>
            <span>Tin nhắn</span>
        </a>
        @if (auth()->check() && auth()->user()->isApprovedInstructor())
            <a class="flex items-center gap-3 px-4 py-3 border-2 border-transparent hover:border-black hover:bg-white/10 transition-colors font-bold pixel-text text-sm"
                href="{{ route('instructor.dashboard') }}">
                <i class="fas fa-chalkboard-teacher text-brand"></i>
                <span>Khu giảng viên</span>
            </a>
        @endif
        <a class="flex items-center gap-3 px-4 py-3 border-2 border-transparent hover:border-black hover:bg-white/10 transition-colors font-bold pixel-text text-sm"
            href="{{ route('user.setting') }}">
            <i class="fas fa-cog text-text-secondary"></i>
            <span>Cài đặt</span>
        </a>
    </nav>

    <!-- User Info -->
    <div class="p-4 border-t-4 border-black mt-auto">
        <div class="bg-black/40 p-3 border-2 border-black flex items-center gap-3">
            <div class="w-10 h-10 bg-brand border-2 border-black flex items-center justify-center">
                <i class="fas fa-user text-black"></i>
            </div>
            <div class="overflow-hidden">
                <p class="text-[10px] text-brand pixel-text font-pixel">Level 42</p>
                <p class="text-xs font-bold truncate">
                    @auth
                        {{ auth()->user()->name }}
                    @else
                        Guest
                    @endauth
                </p>
            </div>
        </div>
        <form method="POST" action="{{ route('user.logout') }}">
            @csrf
            <button type="submit"
                class="mt-2 w-full flex items-center justify-center gap-2 px-4 py-2 border-2 border-black bg-red-500/20 text-red-400 hover:bg-red-500 hover:text-white transition-colors text-xs font-bold pixel-text">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
