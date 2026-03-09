<div id="tab-password" class="tab-panel bg-cyber-surface border-4 border-black pixel-shadow p-10 z-[3] relative hidden">
    <div class="max-w-2xl space-y-10">
        <form action="{{ route('user.passwordSetting') }}" method="POST">
            @csrf
            <div>
                <h3 class="text-2xl font-bold text-white uppercase italic">Thay đổi mật khẩu</h3>
                <p class="text-text-secondary uppercase text-xs tracking-widest mt-1 pixel-text">
                    <i class="fas fa-shield-alt mr-1"></i> Cập nhật thông tin bảo mật
                </p>
            </div>

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


            <div class="space-y-6">
                <!-- Current Password -->
                <div class="space-y-2">
                    <label class="block text-brand uppercase font-bold text-sm tracking-widest pixel-text">
                        <i class="fas fa-key mr-1"></i> Mật khẩu hiện tại:
                    </label>
                    <div class="relative">
                        <input
                            class="w-full bg-black border-4 border-cyber-cyan p-4 text-cyber-cyan font-mono focus:ring-0 focus:border-brand transition-colors pr-12"
                            type="password" name="current_password" id="current_password"
                            placeholder="Nhập mật khẩu hiện tại..." />
                        <button type="button" onclick="togglePassword('current_password')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-text-secondary hover:text-brand transition-colors">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- New Password -->
                <div class="space-y-2">
                    <label class="block text-brand uppercase font-bold text-sm tracking-widest pixel-text">
                        <i class="fas fa-lock mr-1"></i> Mật khẩu mới:
                    </label>
                    <div class="relative">
                        <input
                            class="w-full bg-black border-4 border-cyber-cyan p-4 text-cyber-cyan font-mono focus:ring-0 focus:border-brand transition-colors pr-12"
                            type="password" name="new_password" id="new_password" placeholder="Nhập mật khẩu mới..." />
                        <button type="button" onclick="togglePassword('new_password')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-text-secondary hover:text-brand transition-colors">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm New Password -->
                <div class="space-y-2">
                    <label class="block text-brand uppercase font-bold text-sm tracking-widest pixel-text">
                        <i class="fas fa-check-double mr-1"></i> Xác nhận mật khẩu mới:
                    </label>
                    <div class="relative">
                        <input
                            class="w-full bg-black border-4 border-cyber-cyan p-4 text-cyber-cyan font-mono focus:ring-0 focus:border-brand transition-colors pr-12"
                            type="password" name="new_password_confirmation" id="confirm_password"
                            placeholder="Nhập lại mật khẩu mới..." />
                        <button type="button" onclick="togglePassword('confirm_password')"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-text-secondary hover:text-brand transition-colors">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <p class="text-[10px] text-red-400 pixel-text hidden" id="password-mismatch">
                        <i class="fas fa-exclamation-triangle mr-1"></i> MẬT KHẨU KHÔNG KHỚP
                    </p>
                </div>
            </div>

            <!-- Save Button -->
            <div class="pt-8">
                <button type="submit"
                    class="w-full bg-brand border-4 border-black py-4 text-black font-black text-2xl uppercase italic tracking-tighter pixel-shadow pixel-button-hover">
                    <i class="fas fa-shield-alt mr-2"></i> Cập nhật mật khẩu
                </button>
            </div>
        </form>
    </div>
</div>
