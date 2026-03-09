@extends('frontend.master')

@push('style')
    <style>
        .retro-border {
            border: 4px solid #000000;
        }

        .retro-input::placeholder {
            color: #A6ACCD;
            opacity: 0.7;
        }

        .shadow-retro {
            box-shadow: 4px 4px 0px 0px rgba(0, 0, 0, 1);
        }

        .shadow-retro-hover:hover {
            box-shadow: 2px 2px 0px 0px rgba(0, 0, 0, 1);
        }
    </style>
@endpush

@section('content')
    <div class="flex flex-1 justify-center py-8 px-8 sm:px-12 lg:px-20 overflow-x-hidden">
        <div class="layout-content-container flex flex-col w-full max-w-[1200px] flex-1">

            <!-- Breadcrumb Area -->
            @include('frontend.section.breadcrumb', ['title' => 'Thanh toán'])

            <form id="payment-form" method="post" action="{{ route('order') }}"
                class="flex flex-col lg:flex-row gap-10 mt-8 items-start">
                @csrf
                <!-- Thông tin thanh toán & Phương thức -->
                <div class="flex-1 space-y-10 w-full flex flex-col">
                    <!-- Thông tin thanh toán -->
                    <div class="bg-[#282A3A] retro-border shadow-retro p-6 sm:p-8">
                        <h2
                            class="text-[#4bf425] text-2xl font-black uppercase tracking-wider mb-6 pb-4 border-b-4 border-black flex items-center gap-3">
                            <i class="fas fa-file-invoice"></i> Thông tin thanh toán
                        </h2>

                        @if ($errors->any())
                            <div
                                class="bg-[#FF5252]/20 border-4 border-[#FF5252] retro-border rounded-none p-4 mb-6 shadow-[2px_2px_0_0_#FF5252]">
                                <ul class="text-[#FF5252] text-sm font-bold tracking-wide list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="space-y-5">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="flex flex-col space-y-2">
                                    <label class="font-bold text-sm text-[#A6ACCD]">Họ</label>
                                    <input type="text" name="first_name" value="{{ $user->first_name ?? '' }}"
                                        class="retro-input p-3 retro-border bg-[#1E1E2E] font-bold text-[#00E5FF] focus:outline-none focus:ring-2 focus:ring-[#4bf425] focus:ring-inset placeholder:text-[#00E5FF]/40"
                                        placeholder="VD: Nguyễn" required>
                                </div>
                                <div class="flex flex-col space-y-2">
                                    <label class="font-bold text-sm text-[#A6ACCD]">Tên</label>
                                    <input type="text" name="last_name" value="{{ $user->last_name ?? '' }}"
                                        class="retro-input p-3 retro-border bg-[#1E1E2E] font-bold text-[#00E5FF] focus:outline-none focus:ring-2 focus:ring-[#4bf425] focus:ring-inset placeholder:text-[#00E5FF]/40"
                                        placeholder="VD: A" required>
                                </div>

                            </div>

                            <div class="flex flex-col space-y-2">
                                <label class="font-bold text-sm text-[#A6ACCD]">Số điện thoại</label>
                                <input type="tel" name="phone" value="{{ $user->phone ?? '' }}"
                                    class="retro-input p-3 retro-border bg-[#1E1E2E] font-bold text-[#00E5FF] focus:outline-none focus:ring-2 focus:ring-[#4bf425] focus:ring-inset placeholder:text-[#00E5FF]/40"
                                    placeholder="VD: 0912345678" required>
                            </div>

                            <div class="flex flex-col space-y-2">
                                <label class="font-bold text-sm text-[#A6ACCD]">Email</label>
                                <input type="email" name="email" value="{{ $user->email ?? '' }}"
                                    class="retro-input p-3 retro-border bg-[#1E1E2E] font-bold text-[#00E5FF] focus:outline-none focus:ring-2 focus:ring-[#4bf425] focus:ring-inset placeholder:text-[#00E5FF]/40"
                                    placeholder="VD: email@example.com" required>
                            </div>

                            <div class="flex flex-col space-y-2">
                                <label class="font-bold text-sm text-[#A6ACCD]">Địa chỉ chi tiết</label>
                                <input type="text" name="address" value="{{ $user ? $user->address : '' }}"
                                    class="retro-input p-3 retro-border bg-[#1E1E2E] font-bold text-[#00E5FF] focus:outline-none focus:ring-2 focus:ring-[#4bf425] focus:ring-inset placeholder:text-[#00E5FF]/40"
                                    placeholder="VD: Số nhà, ngõ, phường..." required>
                            </div>

                            <div class="flex flex-col space-y-2">
                                <label class="font-bold text-sm text-[#A6ACCD]">Ghi chú (Tùy chọn)</label>
                                <textarea name="note" rows="3"
                                    class="retro-input p-3 retro-border bg-[#1E1E2E] font-bold text-[#00E5FF] focus:outline-none focus:ring-2 focus:ring-[#4bf425] focus:ring-inset placeholder:text-[#00E5FF]/40"
                                    placeholder="Ghi chú thêm về đơn hàng..."></textarea>
                            </div>

                            <div class="flex items-center space-x-3 mt-4">
                                <input type="checkbox" id="agreeCheckbox"
                                    class="w-5 h-5 accent-[#4bf425] retro-border bg-[#1E1E2E]" required>
                                <label for="agreeCheckbox" class="text-sm font-bold text-[#A6ACCD]">
                                    Tôi đồng ý với <a href="#"
                                        class="text-[#00E5FF] hover:text-[#4bf425] underline transition-colors">Điều khoản
                                        dịch vụ</a> và <a href="#"
                                        class="text-[#00E5FF] hover:text-[#4bf425] underline transition-colors">Chính sách
                                        bảo mật</a>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Phương thức thanh toán -->
                    <div class="bg-[#282A3A] retro-border shadow-retro p-6 sm:p-8">
                        <h2
                            class="text-[#4bf425] text-2xl font-black uppercase tracking-wider mb-6 pb-4 border-b-4 border-black flex items-center gap-3">
                            <i class="fas fa-credit-card"></i> Phương thức thanh toán
                        </h2>

                        <div class="space-y-4">
                            <label
                                class="flex items-center justify-between p-4 retro-border cursor-pointer transition-colors hover:bg-[#1E1E2E]/80 bg-[#1E1E2E]">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" id="stripe" name="payment_type" value="stripe"
                                        class="w-5 h-5 accent-[#4bf425]">
                                    <span class="font-bold text-slate-100 uppercase tracking-wide">Stripe</span>
                                </div>
                                <div class="bg-white p-1 retro-border">
                                    <img src="{{ asset('frontend/images/stripe.png') }}"
                                        onerror="this.onerror=null; this.src='https://cdn.brandfetch.io/idxAg10C0L/theme/dark/logo.svg?c=1bxid64Mup7aczewSAYMX&t=1746435914582'"
                                        alt="Stripe" class="h-6 object-contain">
                                </div>
                            </label>

                            <label
                                class="flex items-center justify-between p-4 retro-border cursor-pointer transition-colors hover:bg-[#1E1E2E]/80 bg-[#1E1E2E]">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" id="vnpay" name="payment_type" value="vnpay"
                                        class="w-5 h-5 accent-[#4bf425]">
                                    <span class="font-bold text-slate-100 uppercase tracking-wide">VNPAY</span>
                                </div>
                                <div class="bg-white p-1 retro-border">
                                    <img src="{{ asset('frontend/images/vnpay.png') }}"
                                        onerror="this.onerror=null; this.src='https://cdn.brandfetch.io/idV02t6WJs/theme/dark/logo.svg?c=1bxid64Mup7aczewSAYMX&t=1766490355643'"
                                        alt="VNPAY" class="h-6 object-contain">
                                </div>
                            </label>

                            <label
                                class="flex items-center justify-between p-4 retro-border cursor-pointer transition-colors hover:bg-[#1E1E2E]/80 bg-[#1E1E2E]">
                                <div class="flex items-center space-x-3">
                                    <input type="radio" id="paypal" name="payment_type" value="paypal"
                                        class="w-5 h-5 accent-[#4bf425]">
                                    <span class="font-bold text-slate-100 uppercase tracking-wide">PayPal</span>
                                </div>
                                <div class="bg-white p-1 retro-border">
                                    <img src="{{ asset('frontend/images/paypal.png') }}"
                                        onerror="this.onerror=null; this.src='https://upload.wikimedia.org/wikipedia/commons/b/b5/PayPal.svg'"
                                        alt="PayPal" class="h-6 object-contain">
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Tổng quan đơn hàng -->
                <div class="w-full lg:w-[400px] flex-shrink-0">
                    <div class="bg-[#282A3A] retro-border shadow-retro p-6 sm:p-8 sticky top-8 flex flex-col gap-6">
                        <h2
                            class="text-[#4bf425] text-2xl font-black uppercase tracking-wider pb-4 border-b-4 border-black flex items-center gap-3">
                            <i class="fas fa-shopping-cart"></i>
                            Đơn hàng của bạn
                        </h2>


                        <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                            @forelse ($cart as $item)
                                <div
                                    class="flex items-start space-x-4 border-b-4 border-black pb-4 last:border-0 last:pb-0">
                                    <div
                                        class="w-16 h-16 flex-shrink-0 retro-border overflow-hidden bg-[#1E1E2E] shadow-[2px_2px_0_0_rgba(0,0,0,1)]">
                                        <img src="{{ asset($item->course->course_image) }}"
                                            onerror="this.onerror=null; this.src='https://placehold.co/100x100?text=Course'"
                                            alt="{{ $item->course->course_name }}" class="w-full h-full object-cover">
                                    </div>
                                    <input type="hidden" name="course_id[]" value="{{ $item->course->id }}">
                                    <input type="hidden" name="course_name[]" value="{{ $item->course->course_name }}">
                                    <input type="hidden" name="course_price[]"
                                        value="{{ $item->course->discount_price ?? $item->course->selling_price }}">
                                    <input type="hidden" name="course_image[]"
                                        value="{{ $item->course->course_image }}">
                                    <input type="hidden" name="instructor_id[]" value="{{ $item->course->user->id }}">
                                    <div class="flex-1">
                                        <h5 class="text-sm font-bold text-slate-100 line-clamp-2 leading-tight mb-1">
                                            {{ $item->course->course_name }}
                                        </h5>
                                        <div class="flex flex-col">
                                            <span
                                                class="font-black text-[#4bf425]">{{ number_format($item->course->discount_price ?? $item->course->selling_price, 0, ',', '.') }}đ</span>
                                            @if ($item->course->discount_price)
                                                <span
                                                    class="text-[#FF5252] line-through text-xs font-bold">{{ number_format($item->course->selling_price, 0, ',', '.') }}đ</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4 font-bold text-[#A6ACCD]">Không có sản phẩm trong đơn hàng
                                </div>
                            @endforelse
                        </div>

                        <div id="couponSection" class="flex flex-col gap-4 mt-4 w-full">
                            <h3 class="text-[#4bf425] text-xl font-bold uppercase tracking-wider">Nhập mã giảm giá</h3>
                            <div class="flex h-14 w-full">
                                <div id="couponForm" class="flex w-full">
                                    @csrf

                                    <div
                                        class="flex flex-1 retro-border bg-[#1E1E2E] border-r-0 focus-within:ring-2 focus-within:ring-[#4bf425] focus-within:ring-inset">
                                        <div class="text-[#00E5FF] flex items-center justify-center pl-4 pr-2">
                                            <i class="fas fa-code text-xl"></i>
                                        </div>
                                        <input
                                            class="retro-input form-input flex-1 bg-transparent border-none text-[#00E5FF] font-bold uppercase tracking-widest focus:ring-0 placeholder:text-[#00E5FF]/40 disabled:opacity-50"
                                            placeholder="Mã giảm giá" type="text" name="coupon" id="couponInput"
                                            @if (session()->has('coupon')) disabled @endif
                                            onkeydown="if(event.key === 'Enter'){ event.preventDefault(); $('#applyCouponBtn').click(); }" />
                                    </div>
                                    <button type="button" id="applyCouponBtn"
                                        @if (session()->has('coupon')) disabled @endif
                                        class="h-full px-8 bg-[#4bf425] text-black font-black uppercase tracking-widest retro-border border-l-4 hover:bg-[#4bf425]/90 active:bg-[#4bf425]/80 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                        {{ session()->has('coupon') ? 'Applied' : 'Execute' }}
                                    </button>
                                </div>
                            </div>
                            <div id="couponMessage" class="mt-2 text-sm font-bold text-[#FF5252]"></div>
                        </div>

                        <div class="flex flex-col gap-4 text-lg font-bold border-t-4 border-black pt-4">
                            <div class="flex justify-between items-center text-slate-100">
                                <span>Tạm tính:</span>
                                <span class="text-[#FFEB3B]">{{ number_format($total ?? 0, 0, ',', '.') }}đ</span>
                            </div>
                            <div id="totalDiscountItem" class="flex justify-between items-center text-[#FF5252]"
                                style="{{ session()->has('coupon') ? '' : 'display: none;' }}">
                                <div class="flex items-center gap-2">
                                    <span>Giảm giá:</span>
                                    <button type="button" id="removeCouponBtn"
                                        class="text-[10px] bg-[#FF5252] text-white px-1.5 py-0.5 retro-border hover:bg-[#FF5252]/80 transition-colors uppercase font-black">X</button>
                                </div>
                                <span
                                    id="totalDiscount">-{{ number_format(session()->get('coupon') ?? 0, 0, ',', '.') }}đ</span>
                            </div>
                            <div class="w-full h-1 bg-black my-2"></div>
                            <div class="flex justify-between items-center text-[#4bf425] text-2xl font-black">
                                <span>Tổng cộng:</span>
                                <span id="totalAmount" class="text-[#FFEB3B] drop-shadow-md">
                                    @if (session()->get('coupon'))
                                        {{ number_format(($total ?? 0) - session()->get('coupon'), 0, ',', '.') }}đ
                                    @else
                                        {{ number_format($total ?? 0, 0, ',', '.') }}đ
                                    @endif
                                </span>
                                <input id="totalPriceInput" type="hidden" name="total_price"
                                    value="{{ $total - (session()->get('coupon') ?? 0) }}">
                            </div>
                        </div>

                        <p class="text-xs text-[#A6ACCD] font-bold flex items-center justify-center space-x-2 mt-2">
                            <i class="fas fa-lock text-[#4bf425]"></i>
                            <span>THANH TOÁN BẢO MẬT & MÃ HÓA</span>
                        </p>

                        <button type="submit"
                            class="mt-2 w-full h-16 bg-[#4bf425] text-black text-xl font-black uppercase tracking-widest retro-border shadow-retro shadow-retro-hover hover:translate-y-[2px] hover:translate-x-[2px] active:bg-[#4bf425]/80 transition-all flex items-center justify-center gap-3 group">
                            <i class="fas fa-check-circle group-hover:animate-pulse"></i>
                            Đặt hàng ngay
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#applyCouponBtn').click(function(e) {
                e.preventDefault();
                let couponData = $('#couponForm :input').serialize();
                let courseData = $('input[name="course_id[]"], input[name="instructor_id[]"]').serialize();
                let formData = couponData + (courseData ? '&' + courseData : '');

                $.ajax({
                    url: "/apply-coupon",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        // Calculate total discount
                        let totalDiscount = response.discounts.reduce((sum, item) => {
                            return sum + parseFloat(item.discount);
                        }, 0);

                        // Update the Discount Amount
                        $('#totalDiscount').text('-' + new Intl.NumberFormat('vi-VN').format(
                            totalDiscount) + 'đ');
                        $('#totalDiscountItem').show(); // Show the discount item

                        // Update the Total Price after applying the discount
                        let subTotal = parseFloat("{{ $total ?? 0 }}");
                        let totalAmount = subTotal - totalDiscount;
                        $('#totalAmount').text(new Intl.NumberFormat('vi-VN').format(
                            totalAmount) + 'đ');

                        // Update hidden input
                        $('#totalPriceInput').val(totalAmount);

                        // Disable the Coupon Form input fields area
                        $('#couponInput').prop('disabled', true);
                        $('#applyCouponBtn').prop('disabled', true).text('Applied');

                        // Success Toast
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Mã giảm giá đã được áp dụng thành công!',
                            showConfirmButton: false,
                            toast: true,
                            timer: 3000,
                            background: '#28a745',
                            color: '#fff'
                        });
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            let errorMessage = '';
                            for (let field in errors) {
                                errorMessage += errors[field].join('<br>') + '<br>';
                            }
                            Swal.fire({
                                position: 'top-end',
                                title: 'Validation Errors',
                                html: errorMessage,
                                icon: 'error',
                                toast: true,
                                timer: 3000,
                                showConfirmButton: false,
                                background: '#dc3545',
                                color: '#fff'
                            });
                        } else {
                            Swal.fire({
                                position: 'top-end',
                                icon: 'error',
                                title: xhr.responseJSON?.message ||
                                    'Áp dụng mã giảm giá thất bại!',
                                showConfirmButton: false,
                                toast: true,
                                timer: 3000,
                                background: '#dc3545',
                                color: '#fff'
                            });
                        }
                    }
                });
            });

            $('#removeCouponBtn').click(function() {
                $.ajax({
                    url: "{{ route('removeCoupon') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        // Update total price back to original
                        let totalAmount = parseFloat("{{ $total ?? 0 }}");
                        $('#totalAmount').text(new Intl.NumberFormat('vi-VN').format(
                            totalAmount) + 'đ');
                        $('#totalPriceInput').val(totalAmount);

                        // Hide discount row
                        $('#totalDiscountItem').hide();

                        // Enable coupon form
                        $('#couponInput').prop('disabled', false).val('');
                        $('#applyCouponBtn').prop('disabled', false).text('Execute');

                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: response.message,
                            showConfirmButton: false,
                            toast: true,
                            timer: 3000,
                            background: '#28a745',
                            color: '#fff'
                        });
                    }
                });
            });
        });
    </script>
@endpush
