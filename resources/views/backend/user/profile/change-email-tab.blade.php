<div id="tab-email" class="tab-panel bg-cyber-surface border-4 border-black pixel-shadow p-10 z-[3] relative hidden">
    <div class="max-w-2xl space-y-10">
        <div>
            <h3 class="text-2xl font-bold text-white uppercase italic">Thay đổi email</h3>
            <p class="text-text-secondary uppercase text-xs tracking-widest mt-1 pixel-text">
                <i class="fas fa-envelope mr-1"></i> Quản lý kênh giao tiếp của bạn
            </p>
        </div>
        <!-- Change Email Form -->
        <form action="{{ route('user.emailSetting') }}" method="POST">
            @csrf
            @if ($errors->any())
                <div class="bg-red-500/10 border-2 border-red-500 p-4 mb-6">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-triangle text-red-500"></i>
                        <span class="text-red-500 font-bold text-sm pixel-text">ĐÃ CÓ LỖI XẢY RA!</span>
                    </div>
                    <ul class="list-disc list-inside text-text-secondary text-sm space-y-1 ml-6">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Current Email Display -->
            <div class="bg-black/50 border-2 border-black p-6">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-cyber-cyan/20 border-2 border-cyber-cyan flex items-center justify-center">
                        <i class="fas fa-envelope text-cyber-cyan text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-text-secondary pixel-text font-bold">EMAIL HIỆN TẠI</p>
                        <p class="text-cyber-cyan text-xl font-bold font-mono">{{ auth()->user()->email }}</p>
                    </div>
                    <div class="ml-auto">
                        <span
                            class="bg-brand/20 text-brand text-[10px] px-3 py-1 border border-brand font-bold pixel-text">
                            <i class="fas fa-check-circle mr-1"></i> VERIFIED
                        </span>
                    </div>
                </div>
            </div>


            <div class="space-y-6">
                <div class="space-y-2">
                    <label class="block text-brand uppercase font-bold text-sm tracking-widest pixel-text">
                        <i class="fas fa-envelope-open mr-1"></i> Email mới
                    </label>
                    <input
                        class="w-full bg-black border-4 border-cyber-cyan p-4 text-cyber-cyan font-mono focus:ring-0 focus:border-brand transition-colors"
                        type="email" name="new_email" placeholder="Nhập email mới..." />
                </div>

                <div class="space-y-2">
                    <label class="block text-brand uppercase font-bold text-sm tracking-widest pixel-text">
                        <i class="fas fa-lock mr-1"></i> Xác nhận bằng mật khẩu:
                    </label>
                    <input
                        class="w-full bg-black border-4 border-cyber-cyan p-4 text-cyber-cyan font-mono focus:ring-0 focus:border-brand transition-colors"
                        type="password" name="password" placeholder="Nhập mật khẩu của bạn..." />
                </div>
            </div>

            <!-- Warning Box -->
            <div class="mt-6 bg-yellow-400/10 border-2 border-yellow-400 p-4 flex items-start gap-3">
                <i class="fas fa-exclamation-triangle text-yellow-400 mt-0.5"></i>
                <div>
                    <p class="text-yellow-400 font-bold text-sm pixel-text">CẢNH BÁO</p>
                    <p class="text-text-secondary text-xs mt-1">Thay đổi email sẽ yêu cầu xác minh lại.
                        Một liên kết xác nhận sẽ được gửi đến địa chỉ email mới của bạn.</p>
                </div>
            </div>

            <div class="pt-8">
                <button type="submit"
                    class="w-full bg-brand border-4 border-black py-4 text-black font-black text-2xl uppercase italic tracking-tighter pixel-shadow pixel-button-hover">
                    <i class="fas fa-envelope mr-2"></i> Cập nhật email
                </button>
            </div>
        </form>
    </div>
</div>
