<div class="offcanvas-overlay fixed inset-0 bg-black/70 z-[60]" id="menuOverlay"></div>
<div class="offcanvas fixed top-0 left-0 h-full w-72 bg-cyber-dark border-r-4 border-black z-[70] overflow-y-auto"
    id="mobileMenu">
    <div class="flex items-center justify-between p-4 border-b-2 border-black">
        <span class="font-bold uppercase text-brand">Menu</span>
        <button id="closeMenu" class="text-xl hover:text-brand"><i class="fas fa-times"></i></button>
    </div>
    <nav class="p-4 space-y-1">
        <a href="{{ route('frontend.home') }}" class="block py-3 px-3 bg-brand/10 border-l-4 border-brand font-bold">Home</a>
        <a href="{{ route('frontend.courses.index') }}" class="block py-3 px-3 hover:bg-cyber-surface font-bold">Khóa học</a>
        <a href="{{ route('cart') }}" class="block py-3 px-3 hover:bg-cyber-surface font-bold">Giỏ hàng</a>
        <a href="#" class="block py-3 px-3 hover:bg-cyber-surface font-bold">Blog</a>
    </nav>
    <div class="p-4 border-t-2 border-black">
        <p class="text-xs text-text-secondary uppercase font-bold mb-3"><i class="fas fa-th mr-1"></i> Categories
        </p>
        @foreach (getCategories() as $item)
            <a href="#" class="block py-2 px-3 text-sm hover:text-brand">{{ $item->name }}</a>
        @endforeach
    </div>
    <div class="p-4 border-t-2 border-black">
        @if (!auth()->user())
            <a href="{{ route('login') }}"
                class="block w-full bg-brand text-black text-center py-3 font-bold uppercase pixel-border text-sm mb-2">Sign
                In</a>
            <a href="{{ route('register') }}"
                class="block w-full bg-cyber-surface text-text-primary text-center py-3 font-bold uppercase pixel-border text-sm">Register</a>
        @else
            @php
                $dashboardRoute = auth()->user()->preferredDashboardRoute();
            @endphp
            <a href="{{ route($dashboardRoute) }}"
                class="block w-full bg-brand text-black text-center py-3 font-bold uppercase pixel-border text-sm mb-2">Dashboard</a>
            <form id="mobile-logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                @csrf
            </form>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('mobile-logout-form').submit();"
                class="block w-full bg-red-600 text-white text-center py-3 font-bold uppercase pixel-border text-sm">Đăng
                xuất</a>
        @endif
    </div>
</div>
