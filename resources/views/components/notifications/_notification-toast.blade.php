{{-- Notification Toast Component --}}
{{-- Được gọi bởi Alpine.js notificationHub component khi nhận thông báo real-time --}}

<div x-data="notificationToast()" x-cloak
    class="fixed top-4 right-4 z-[9999] flex flex-col gap-2 pointer-events-none"
    style="max-width: 380px;">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-8"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-8"
            class="pointer-events-auto bg-cyber-dark border-2 border-brand/50 shadow-lg shadow-brand/20 overflow-hidden"
            style="backdrop-filter: blur(12px);">

            {{-- Progress Bar --}}
            <div class="h-0.5 bg-brand/30">
                <div class="h-full bg-brand transition-all duration-100 ease-linear"
                    :style="'width: ' + toast.progress + '%'"></div>
            </div>

            <div class="flex items-start gap-3 p-3">
                {{-- Icon --}}
                <div class="w-9 h-9 border border-brand/40 bg-brand/10 flex items-center justify-center shrink-0">
                    <i :class="toast.icon || 'fas fa-bell'" class="text-brand text-sm"></i>
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-text-primary truncate" x-text="toast.title"></p>
                    <p class="text-xs text-text-secondary mt-0.5 line-clamp-2" x-text="toast.body"></p>
                </div>

                {{-- Close --}}
                <button @click="dismissToast(toast.id)"
                    class="text-text-secondary hover:text-white text-xs shrink-0 mt-0.5 transition-colors">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </template>
</div>
