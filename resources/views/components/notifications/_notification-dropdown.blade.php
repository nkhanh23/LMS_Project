{{-- Notification Dropdown Component --}}
{{-- Sử dụng: @include('components.notifications._notification-dropdown', ['variant' => 'cyber|bootstrap']) --}}

@php
    $unreadCount = auth()->user()->unreadNotifications()->count();
    $latestNotifications = auth()->user()->notifications()->take(5)->get();
@endphp

@if(($variant ?? 'cyber') === 'cyber')
{{-- ===== CYBER / TAILWIND VARIANT (User Dashboard) ===== --}}
<div class="relative" x-data="notificationHub({{ auth()->id() }}, {{ $unreadCount }})" @click.away="open = false">
    <button @click="open = !open"
        class="relative w-12 h-12 bg-cyber-surface border-2 border-black pixel-shadow-sm pixel-button-hover flex items-center justify-center">
        <i class="fas fa-bell"></i>
        <div x-show="unreadCount > 0" x-cloak
            class="absolute top-1 right-1 w-3 h-3 bg-red-600 border border-black animate-pulse"></div>
    </button>

    {{-- Dropdown --}}
    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="absolute top-full right-0 pt-2 w-80 z-50">
        <div class="bg-cyber-surface border-2 border-black pixel-shadow">
            {{-- Header --}}
            <div class="px-4 py-3 border-b-2 border-black flex items-center justify-between">
                <h4 class="font-bold text-sm pixel-text text-brand">Thông báo</h4>
                <div class="flex items-center gap-2">
                    <span x-show="unreadCount > 0" x-text="unreadCount + ' MỚI'"
                        class="text-[10px] bg-red-600 text-white px-2 py-0.5 border border-black font-bold"></span>
                    <button @click.stop="markAllAsRead()" x-show="unreadCount > 0"
                        class="text-[10px] text-cyber-cyan hover:text-brand transition-colors font-mono"
                        title="Đánh dấu tất cả đã đọc">
                        <i class="fas fa-check-double"></i>
                    </button>
                </div>
            </div>

            {{-- List --}}
            <div class="max-h-72 overflow-y-auto">
                <template x-if="notifications.length === 0">
                    <div class="px-4 py-8 text-center text-text-secondary text-sm">
                        <i class="fas fa-bell-slash text-2xl mb-2 block opacity-50"></i>
                        Chưa có thông báo nào
                    </div>
                </template>

                <template x-for="notif in notifications" :key="notif.id">
                    <a :href="'#'" @click.prevent="handleNotifClick(notif)"
                        class="flex items-start gap-3 px-4 py-3 border-b border-black/30 hover:bg-white/5 transition-colors"
                        :class="{ 'bg-brand/5': !notif.read_at }">
                        <div class="w-8 h-8 border flex items-center justify-center shrink-0 mt-0.5"
                            :class="getIconClasses(notif)">
                            <i :class="notif.data?.icon || 'fas fa-bell'" class="text-xs"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold truncate" x-text="notif.data?.title || 'Thông báo'"></p>
                            <p class="text-xs text-text-secondary line-clamp-2" x-text="notif.data?.body || ''"></p>
                            <span class="text-[10px] text-text-secondary mt-1 block" x-text="timeAgo(notif.created_at)"></span>
                        </div>
                        <div x-show="!notif.read_at" class="w-2 h-2 bg-brand mt-2 shrink-0"></div>
                    </a>
                </template>
            </div>

            {{-- Footer --}}
            <a href="#"
                class="block px-4 py-3 text-center text-xs font-bold text-brand pixel-text hover:bg-white/5 transition-colors border-t-2 border-black">
                XEM TẤT CẢ THÔNG BÁO
            </a>
        </div>
    </div>
</div>

@else
{{-- ===== BOOTSTRAP VARIANT (Admin + Instructor) ===== --}}
<li class="nav-item dropdown dropdown-large" x-data="notificationHub({{ auth()->id() }}, {{ $unreadCount }})">
    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#"
        @click.prevent="open = !open">
        <span class="alert-count" x-text="unreadCount" x-show="unreadCount > 0" x-cloak></span>
        <i class='bx bx-bell'></i>
    </a>

    <div class="dropdown-menu dropdown-menu-end p-0" :class="{ 'show': open }" @click.away="open = false"
        style="min-width: 320px;">
        <div class="msg-header d-flex justify-content-between align-items-center">
            <p class="msg-header-title mb-0">Thông báo</p>
            <div class="d-flex align-items-center gap-2">
                <p class="msg-header-badge mb-0" x-show="unreadCount > 0" x-text="unreadCount + ' Mới'" x-cloak></p>
                <button @click.stop="markAllAsRead()" x-show="unreadCount > 0" x-cloak
                    class="btn btn-sm btn-link p-0 text-primary" title="Đánh dấu tất cả đã đọc">
                    <i class="bx bx-check-double"></i>
                </button>
            </div>
        </div>

        <div class="header-notifications-list" style="max-height: 300px; overflow-y: auto;">
            <template x-if="notifications.length === 0">
                <div class="text-center py-4 text-muted">
                    <i class="bx bx-bell-off fs-3 d-block mb-2"></i>
                    <small>Chưa có thông báo nào</small>
                </div>
            </template>

            <template x-for="notif in notifications" :key="notif.id">
                <a class="dropdown-item" :href="'#'" @click.prevent="handleNotifClick(notif)"
                    :style="!notif.read_at ? 'background-color: rgba(0,123,255,0.05)' : ''">
                    <div class="d-flex align-items-center">
                        <div class="notify" :class="'bg-light-' + getBootstrapColor(notif)">
                            <i :class="notif.data?.icon || 'bx bx-bell'"></i>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <h6 class="msg-name mb-0" x-text="notif.data?.title || 'Thông báo'">
                                <span class="msg-time float-end" x-text="timeAgo(notif.created_at)"></span>
                            </h6>
                            <p class="msg-info mb-0" x-text="truncateText(notif.data?.body || '', 60)"></p>
                        </div>
                    </div>
                </a>
            </template>
        </div>

        <a href="#" class="text-center msg-footer d-block py-2">
            Xem tất cả thông báo
        </a>
    </div>
</li>
@endif
