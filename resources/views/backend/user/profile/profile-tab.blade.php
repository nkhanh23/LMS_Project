<div id="tab-profile" class="tab-panel bg-cyber-surface border-4 border-black pixel-shadow p-10 z-[3] relative">
    <div class="max-w-4xl space-y-10">
        <!-- Avatar Section -->
        <form action="{{ route('user.profile.store') }}" method="POST" enctype="multipart/form-data">
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
            <div class="flex items-center gap-8">
                <div class="w-32 h-32 border-4 border-black bg-black p-1 pixel-shadow">
                    <div class="w-full h-full bg-cyber-dark flex items-center justify-center overflow-hidden">
                        @if (auth()->user()->photo)
                            <img src="{{ asset(auth()->user()->photo) }}" alt="User Avatar"
                                class="w-full h-full object-cover" id="avatar-preview" />
                        @else
                            <i class="fas fa-user-astronaut text-brand/30 text-5xl" id="avatar-placeholder"></i>
                        @endif
                    </div>
                </div>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-2xl font-bold text-white uppercase italic">Thông tin cá nhân</h3>
                        <p class="text-text-secondary uppercase text-xs tracking-widest mt-1 pixel-text">Cập nhật thông
                            tin
                            cá nhân</p>
                    </div>
                    <label
                        class="bg-yellow-400 border-4 border-black px-6 py-2 text-black font-black uppercase tracking-tighter pixel-shadow pixel-button-hover cursor-pointer inline-block">
                        Ảnh đại diện
                        <input type="file" name="photo" accept="image/*" class="hidden"
                            onchange="previewPhoto(this)" />
                    </label>
                </div>
            </div>

            <!-- Profile Form -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- First Name -->
                <div class="space-y-2">
                    <label class="block text-brand uppercase font-bold text-sm tracking-widest pixel-text">
                        <i class="fas fa-user mr-1"></i> Họ
                    </label>
                    <input
                        class="w-full bg-black border-4 border-cyber-cyan p-4 text-cyber-cyan font-mono focus:ring-0 focus:border-brand transition-colors"
                        type="text" name="first_name" value="{{ old('first_name', auth()->user()->first_name) }}"
                        placeholder="Nhập họ..." required />
                </div>

                <!-- Last Name -->
                <div class="space-y-2">
                    <label class="block text-brand uppercase font-bold text-sm tracking-widest pixel-text">
                        <i class="fas fa-user mr-1"></i> Tên đệm
                    </label>
                    <input
                        class="w-full bg-black border-4 border-cyber-cyan p-4 text-cyber-cyan font-mono focus:ring-0 focus:border-brand transition-colors"
                        type="text" name="last_name" value="{{ old('last_name', auth()->user()->last_name) }}"
                        placeholder="Nhập tên đệm..." />
                </div>

                <!-- User Name -->
                <div class="space-y-2">
                    <label class="block text-brand uppercase font-bold text-sm tracking-widest pixel-text">
                        <i class="fas fa-at mr-1"></i> Tên người dùng
                    </label>
                    <input
                        class="w-full bg-black border-4 border-cyber-cyan p-4 text-cyber-cyan font-mono focus:ring-0 focus:border-brand transition-colors"
                        type="text" name="username" value="{{ old('name', auth()->user()->name) }}"
                        placeholder="Nhập tên người dùng..." />
                </div>

                <!-- Email (read-only here, editable in Email tab) -->
                <div class="space-y-2">
                    <label class="block text-brand uppercase font-bold text-sm tracking-widest pixel-text">
                        <i class="fas fa-envelope mr-1"></i> Email:
                    </label>
                    <input
                        class="w-full bg-black border-4 border-slate-600 p-4 text-text-secondary font-mono cursor-not-allowed"
                        type="email" name="email" value="{{ auth()->user()->email }}" readonly />
                    <p class="text-[10px] text-text-secondary pixel-text">
                        <i class="fas fa-lock mr-1"></i> Thay đổi email ở tab Email
                    </p>
                </div>

                <!-- Phone Number -->
                <div class="space-y-2">
                    <label class="block text-brand uppercase font-bold text-sm tracking-widest pixel-text">
                        <i class="fas fa-phone mr-1"></i> Số điện thoại
                    </label>
                    <input
                        class="w-full bg-black border-4 border-cyber-cyan p-4 text-cyber-cyan font-mono focus:ring-0 focus:border-brand transition-colors"
                        type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                        placeholder="Nhập số điện thoại..." />
                </div>

                <!-- Address (optional bonus) -->
                <div class="space-y-2">
                    <label class="block text-brand uppercase font-bold text-sm tracking-widest pixel-text">
                        <i class="fas fa-map-marker-alt mr-1"></i> Địa chỉ:
                    </label>
                    <input
                        class="w-full bg-black border-4 border-cyber-cyan p-4 text-cyber-cyan font-mono focus:ring-0 focus:border-brand transition-colors"
                        type="text" name="address" value="{{ old('address', auth()->user()->address) }}"
                        placeholder="Nhập địa chỉ..." />
                </div>

                <!-- Bio -->
                <div class="space-y-2 md:col-span-2">
                    <label class="block text-brand uppercase font-bold text-sm tracking-widest pixel-text">
                        <i class="fas fa-terminal mr-1"></i> Bio:
                    </label>
                    <textarea
                        class="w-full bg-black border-4 border-cyber-cyan p-4 text-cyber-cyan font-mono focus:ring-0 focus:border-brand transition-colors"
                        name="bio" rows="4" placeholder="Giới thiệu về bản thân...">{{ old('bio', auth()->user()->bio) }}</textarea>
                </div>
            </div>

            <!-- Save Button -->
            <div class="pt-8">
                <button type="submit"
                    class="w-full bg-brand border-4 border-black py-4 text-black font-black text-2xl uppercase italic tracking-tighter pixel-shadow pixel-button-hover">
                    <i class="fas fa-save mr-2"></i> Lưu thay đổi
                </button>
            </div>
        </form>
    </div>
</div>
