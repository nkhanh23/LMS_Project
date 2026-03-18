@extends('backend.user.master')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
        <div class="lg:col-span-8 lg:col-start-3">
            <div class="bg-cyber-surface border-4 border-black pixel-shadow flex flex-col">
                <!-- Terminal Title Bar -->
                <div class="bg-black p-3 border-b-4 border-black flex justify-between items-center">
                    <span class="text-brand font-bold pixel-text text-sm">Đăng kí làm giảng viên</span>
                    <div class="flex gap-2">
                        <div class="w-3 h-3 bg-red-500 border border-black"></div>
                        <div class="w-3 h-3 bg-yellow-500 border border-black"></div>
                        <div class="w-3 h-3 bg-green-500 border border-black"></div>
                    </div>
                </div>

                <!-- Content Body -->
                <div class="p-8 space-y-6">
                    @if (session('success'))
                        <div class="bg-black/50 p-4 border-2 border-brand text-brand mb-6 pixel-shadow-sm">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                <span class="pixel-text font-bold text-xs">{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="bg-black/50 p-4 border-2 border-red-500 text-red-500 mb-6 pixel-shadow-sm">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span class="pixel-text font-bold text-xs">{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    @if ($latestRequest && $latestRequest->status === 'pending')
                        <div class="bg-black/50 p-4 border-2 border-yellow-500 text-yellow-500 mb-6 pixel-shadow-sm">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-hourglass-half"></i>
                                <span class="pixel-text font-bold text-xs">STATUS: PENDING_APPROVAL</span>
                            </div>
                            <p class="text-xs">Yêu cầu của bạn đang chờ hệ thống xử lý.</p>
                        </div>
                    @elseif($latestRequest && $latestRequest->status === 'rejected')
                        <div class="bg-black/50 p-4 border-2 border-red-500 text-red-500 mb-6 pixel-shadow-sm">
                            <div class="flex items-center gap-2 mb-1">
                                <i class="fas fa-times-circle"></i>
                                <span class="pixel-text font-bold text-xs">STATUS: REJECTED</span>
                            </div>
                            <p class="text-xs"><strong>Lý do:</strong>
                                {{ $latestRequest->admin_note ?? 'Không có ghi chú' }}</p>
                        </div>
                    @endif

                    <form action="{{ route('user.become-instructor.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Headline Section -->
                        <div class="group">
                            <label class="text-text-secondary text-xs uppercase font-bold mb-2 block pixel-text">Giới thiệu
                                ngắn về bản thân</label>
                            <input type="text" name="headline" value="{{ old('headline') }}"
                                class="w-full bg-black/50 border-2 border-slate-700 focus:border-brand p-3 text-brand font-bold outline-none transition-colors"
                                placeholder="E.G. SENIOR FULL-STACK DEVELOPER">
                        </div>

                        <!-- Phone Section -->
                        <div class="group">
                            <label class="text-text-secondary text-xs uppercase font-bold mb-2 block pixel-text">Số điện
                                thoại / Link mạng xã hội</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                                class="w-full bg-black/50 border-2 border-slate-700 focus:border-brand p-3 text-text-primary font-bold outline-none transition-colors"
                                placeholder="+XX XXX XXX XXX">
                        </div>

                        <!-- Bio Section -->
                        <div class="group">
                            <label class="text-text-secondary text-xs uppercase font-bold mb-2 block pixel-text">Giới thiệu
                                về bản thân</label>
                            <textarea name="bio" rows="4" required
                                class="w-full bg-black/50 border-2 border-slate-700 focus:border-brand p-3 text-text-secondary leading-relaxed font-mono text-sm outline-none transition-colors"
                                placeholder="DESCRIBE YOUR TEACHING PHILOSOPHY...">{{ old('bio') }}</textarea>
                        </div>

                        <!-- Experience Section -->
                        <div class="group">
                            <label class="text-text-secondary text-xs uppercase font-bold mb-2 block pixel-text">Kinh nghiệm
                                giảng dạy</label>
                            <textarea name="experience" rows="4" required
                                class="w-full bg-black/50 border-2 border-slate-700 focus:border-brand p-3 text-text-secondary leading-relaxed font-mono text-sm outline-none transition-colors"
                                placeholder="YEARS IN THE NEON LIGHTS...">{{ old('experience') }}</textarea>
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4">
                            <button type="submit"
                                class="w-full bg-brand text-black font-black text-xl py-4 border-4 border-black pixel-shadow pixel-button-hover uppercase">
                                Gửi yêu cầu
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Footer Stats -->
                <div
                    class="bg-black p-3 border-t-4 border-black flex justify-between items-center text-[10px] pixel-text text-text-secondary">
                    <p>FORM_UID: {{ strtoupper(substr(md5(auth()->id()), 0, 8)) }}</p>
                    <p>ENCRYPTION: AES-256-GCM</p>
                </div>
            </div>
        </div>
    </div>
@endsection
