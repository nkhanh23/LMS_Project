@extends('backend.user.master')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4">
            <div>
                <h3 class="pixel-text font-bold text-xl text-white uppercase tracking-tighter">
                    Cài đặt <span class="text-brand">_SETTINGS</span>
                </h3>
                <p class="text-xs text-text-secondary mt-1 font-pixel">
                    Quản lý thông báo học tập và yêu cầu liên quan đến tài khoản.
                </p>
            </div>

            <div class="inline-flex items-center gap-2 px-3 py-2 bg-black/30 border-2 border-black text-[10px] text-text-secondary font-pixel uppercase">
                <i class="fas fa-user-shield text-brand"></i>
                {{ auth()->user()->email }}
            </div>
        </div>

        @if ($errors->any())
            <div class="bg-red-500/10 border-2 border-red-500 pixel-shadow p-4">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-triangle text-red-400 mt-1"></i>
                    <div>
                        <p class="text-red-400 font-bold text-sm uppercase pixel-text">Không thể xử lý yêu cầu</p>
                        <ul class="mt-2 space-y-1 text-xs text-text-secondary">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-[1fr_420px] gap-6">
            <form action="{{ route('user.setting.notifications.update') }}" method="POST"
                class="bg-cyber-surface border-2 border-black pixel-shadow overflow-hidden">
                @csrf

                <div class="p-5 border-b-2 border-black bg-black/20 flex items-center justify-between gap-4">
                    <div>
                        <h4 class="text-white font-bold uppercase pixel-text">Thông báo</h4>
                        <p class="text-xs text-text-secondary mt-1">
                            Chọn loại thông báo bạn muốn nhận trong hệ thống.
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-brand border-2 border-black flex items-center justify-center text-black">
                        <i class="fas fa-bell"></i>
                    </div>
                </div>

                <div class="divide-y-2 divide-black">
                    <label class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5 hover:bg-white/5 transition-colors cursor-pointer">
                        <span class="flex items-start gap-4">
                            <span class="w-11 h-11 bg-cyber-dark border-2 border-black flex items-center justify-center shrink-0">
                                <i class="fas fa-graduation-cap text-brand"></i>
                            </span>
                            <span>
                                <span class="block text-white font-bold text-sm">Nhận thông báo khóa học mới</span>
                                <span class="block text-xs text-text-secondary mt-1">
                                    Gợi ý khóa học mới, khóa học nổi bật hoặc khóa học từ giảng viên bạn quan tâm.
                                </span>
                            </span>
                        </span>

                        <span class="relative inline-flex items-center shrink-0">
                            <input type="hidden" name="notify_new_courses" value="0">
                            <input type="checkbox" name="notify_new_courses" value="1"
                                class="peer sr-only" @checked($settings->notify_new_courses)>
                            <span class="w-14 h-8 bg-black border-2 border-black peer-checked:bg-brand transition-colors"></span>
                            <span class="absolute left-1 top-1 w-6 h-6 bg-text-secondary border-2 border-black peer-checked:translate-x-6 peer-checked:bg-black transition-transform"></span>
                        </span>
                    </label>

                    <label class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5 hover:bg-white/5 transition-colors cursor-pointer">
                        <span class="flex items-start gap-4">
                            <span class="w-11 h-11 bg-cyber-dark border-2 border-black flex items-center justify-center shrink-0">
                                <i class="fas fa-clock text-cyber-cyan"></i>
                            </span>
                            <span>
                                <span class="block text-white font-bold text-sm">Nhận nhắc học tiếp</span>
                                <span class="block text-xs text-text-secondary mt-1">
                                    Nhắc bạn quay lại bài học đang dở và duy trì tiến độ khóa học.
                                </span>
                            </span>
                        </span>

                        <span class="relative inline-flex items-center shrink-0">
                            <input type="hidden" name="notify_learning_reminders" value="0">
                            <input type="checkbox" name="notify_learning_reminders" value="1"
                                class="peer sr-only" @checked($settings->notify_learning_reminders)>
                            <span class="w-14 h-8 bg-black border-2 border-black peer-checked:bg-brand transition-colors"></span>
                            <span class="absolute left-1 top-1 w-6 h-6 bg-text-secondary border-2 border-black peer-checked:translate-x-6 peer-checked:bg-black transition-transform"></span>
                        </span>
                    </label>

                    <label class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-5 hover:bg-white/5 transition-colors cursor-pointer">
                        <span class="flex items-start gap-4">
                            <span class="w-11 h-11 bg-cyber-dark border-2 border-black flex items-center justify-center shrink-0">
                                <i class="fas fa-comments text-pink-400"></i>
                            </span>
                            <span>
                                <span class="block text-white font-bold text-sm">Nhận thông báo quiz, bình luận, tin nhắn</span>
                                <span class="block text-xs text-text-secondary mt-1">
                                    Cập nhật khi có kết quả quiz, phản hồi thảo luận hoặc tin nhắn mới.
                                </span>
                            </span>
                        </span>

                        <span class="relative inline-flex items-center shrink-0">
                            <input type="hidden" name="notify_quiz_discussion_messages" value="0">
                            <input type="checkbox" name="notify_quiz_discussion_messages" value="1"
                                class="peer sr-only" @checked($settings->notify_quiz_discussion_messages)>
                            <span class="w-14 h-8 bg-black border-2 border-black peer-checked:bg-brand transition-colors"></span>
                            <span class="absolute left-1 top-1 w-6 h-6 bg-text-secondary border-2 border-black peer-checked:translate-x-6 peer-checked:bg-black transition-transform"></span>
                        </span>
                    </label>
                </div>

                <div class="p-5 border-t-2 border-black bg-black/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <p class="text-[10px] text-text-secondary font-pixel uppercase">
                        Các tùy chọn này dùng cho notification hub và email sau khi luồng gửi thông báo đọc cấu hình.
                    </p>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-brand text-black border-2 border-black font-bold text-xs pixel-shadow-sm pixel-button-hover uppercase pixel-text">
                        <i class="fas fa-save text-[10px]"></i>
                        Lưu cài đặt
                    </button>
                </div>
            </form>

            <div class="space-y-6">
                <div class="bg-cyber-surface border-2 border-black pixel-shadow p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 bg-cyber-cyan border-2 border-black flex items-center justify-center text-black">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-bold uppercase pixel-text">Trạng thái tài khoản</h4>
                            <p class="text-xs text-text-secondary mt-1">Thông tin xử lý yêu cầu hiện tại.</p>
                        </div>
                    </div>

                    @if ($settings->account_deletion_requested_at)
                        <div class="bg-yellow-400/10 border-2 border-yellow-400/60 p-4">
                            <p class="text-yellow-400 text-xs font-bold uppercase pixel-text">Đã yêu cầu xóa tài khoản</p>
                            <p class="text-xs text-text-secondary mt-2">
                                Gửi lúc {{ $settings->account_deletion_requested_at->format('d/m/Y H:i') }}.
                            </p>
                        </div>
                    @else
                        <div class="bg-black/30 border-2 border-black p-4">
                            <p class="text-brand text-xs font-bold uppercase pixel-text">Tài khoản đang hoạt động</p>
                            <p class="text-xs text-text-secondary mt-2">
                                Chưa có yêu cầu xóa tài khoản nào được ghi nhận.
                            </p>
                        </div>
                    @endif
                </div>

                <form action="{{ route('user.setting.account-deletion.request') }}" method="POST"
                    class="bg-red-500/5 border-2 border-red-500/60 pixel-shadow p-5 space-y-5">
                    @csrf

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-red-500/20 border-2 border-red-500 flex items-center justify-center shrink-0">
                            <i class="fas fa-trash-alt text-red-400"></i>
                        </div>
                        <div>
                            <h4 class="text-red-400 font-bold uppercase pixel-text">Yêu cầu xóa tài khoản</h4>
                            <p class="text-xs text-text-secondary mt-2 leading-relaxed">
                                Gửi yêu cầu để quản trị viên xử lý xóa tài khoản. Tiến độ học, chứng chỉ, đơn hàng và lịch sử thanh toán cần được kiểm tra trước khi xóa vĩnh viễn.
                            </p>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-red-400 uppercase font-bold text-xs tracking-widest pixel-text">
                            Lý do yêu cầu
                        </label>
                        <textarea name="account_deletion_reason" rows="4"
                            class="w-full bg-black border-2 border-red-500/50 p-3 text-sm text-white focus:ring-0 focus:border-red-500"
                            placeholder="Nhập lý do nếu bạn muốn cung cấp thêm thông tin...">{{ old('account_deletion_reason', $settings->account_deletion_reason) }}</textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-red-400 uppercase font-bold text-xs tracking-widest pixel-text">
                            Xác nhận mật khẩu
                        </label>
                        <input type="password" name="password"
                            class="w-full bg-black border-2 border-red-500/50 p-3 text-sm text-red-100 focus:ring-0 focus:border-red-500"
                            placeholder="Nhập mật khẩu hiện tại">
                    </div>

                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="confirm_delete_request" value="1"
                            class="mt-1 bg-black border-2 border-red-500 text-red-500 focus:ring-red-500" required>
                        <span class="text-xs text-text-secondary">
                            Tôi hiểu đây là yêu cầu xóa tài khoản và cần được hệ thống xử lý trước khi dữ liệu bị xóa.
                        </span>
                    </label>

                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-red-500 text-white border-2 border-black font-bold text-xs pixel-shadow-sm pixel-button-hover uppercase pixel-text">
                        <i class="fas fa-paper-plane text-[10px]"></i>
                        Gửi yêu cầu xóa tài khoản
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
