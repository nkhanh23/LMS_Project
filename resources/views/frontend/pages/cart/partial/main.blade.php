            <div class="flex flex-col lg:flex-row gap-10 items-start">
                <div class="flex-1 w-full flex flex-col gap-10">
                    <div class="@container w-full">
                        <div class="flex overflow-hidden retro-border bg-[#282A3A] shadow-retro">
                            <table class="flex-1 w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-[#1E1E2E] border-b-4 border-black">
                                        <th
                                            class="px-6 py-4 text-center text-[#4bf425] text-base font-bold uppercase tracking-widest border-r-4 border-black w-32">
                                            Hình ảnh</th>
                                        <th
                                            class="px-6 py-4 text-left text-[#4bf425] text-base font-bold uppercase tracking-widest border-r-4 border-black">
                                            Chi tiết</th>
                                        <th
                                            class="px-6 py-4 text-center text-[#4bf425] text-base font-bold uppercase tracking-widest border-r-4 border-black w-40">
                                            Giá</th>
                                        <th
                                            class="px-6 py-4 text-center text-[#4bf425] text-base font-bold uppercase tracking-widest w-24">
                                            Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($cart as $item)
                                        <tr
                                            class="border-b-4 border-black bg-[#282A3A] hover:bg-[#282A3A]/80 transition-colors">
                                            <td class="px-6 py-5 border-r-4 border-black text-center">
                                                <div
                                                    class="w-20 h-20 mx-auto bg-[#1E1E2E] retro-border overflow-hidden shadow-[2px_2px_0_0_rgba(0,0,0,1)]">
                                                    <img src="{{ asset($item->course->course_image) }}"
                                                        alt="{{ $item->course->course_name }}"
                                                        class="w-full h-full object-cover">
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 text-slate-100 border-r-4 border-black">
                                                <h4 class="text-xl font-bold mb-2 line-clamp-2">
                                                    {{ $item->course->course_name }}
                                                </h4>
                                                <p class="text-sm font-bold text-[#A6ACCD]">
                                                    By <a href="#"
                                                        class="text-[#00E5FF] hover:text-[#4bf425] transition-colors">{{ $item->course->user->name }}</a>
                                                </p>
                                            </td>
                                            <td class="px-6 py-5 text-center border-r-4 border-black">
                                                <div class="flex flex-col items-center justify-center gap-1">
                                                    @if ($item->course->discount_price == null)
                                                        <span
                                                            class="text-[#4bf425] text-xl font-black">${{ $item->course->selling_price }}</span>
                                                    @else
                                                        <span
                                                            class="text-[#4bf425] text-xl font-black">${{ $item->course->discount_price }}</span>
                                                        <span
                                                            class="text-[#FF5252] text-sm font-bold line-through">${{ $item->course->selling_price }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 text-center">
                                                <button type="button" data-id="{{ $item->id }}"
                                                    class="remove-course-btn text-[#FF5252] hover:text-[#FF5252]/80 transition-colors flex items-center justify-center w-full">
                                                    <i class="fas fa-trash-alt text-2xl drop-shadow-md"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4"
                                                class="text-center py-5 text-slate-100 text-lg font-bold">
                                                Không có sản phẩm trong giỏ hàng</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex flex-col gap-4 mt-4 w-full max-w-xl">
                        <h3 class="text-[#4bf425] text-xl font-bold uppercase tracking-wider">Nhập mã giảm giá</h3>
                        <div class="flex h-14 w-full">
                            <form id="couponForm" class="flex w-full">
                                @csrf
                                @foreach ($cart as $item)
                                    <input type="hidden" name="course_id[]" value="{{ $item->course->id }}">
                                    <input type="hidden" name="instructor_id[]" value="{{ $item->course->user->id }}">
                                @endforeach

                                @if (!session()->get('coupon'))
                                    <div
                                        class="flex flex-1 retro-border bg-[#1E1E2E] border-r-0 focus-within:ring-2 focus-within:ring-[#4bf425] focus-within:ring-inset">
                                        <div class="text-[#00E5FF] flex items-center justify-center pl-4 pr-2">
                                            <i class="fas fa-code text-xl"></i>
                                        </div>
                                        <input
                                            class="retro-input form-input flex-1 bg-transparent border-none text-[#00E5FF] font-bold uppercase tracking-widest focus:ring-0 placeholder:text-[#00E5FF]/40"
                                            placeholder="Mã giảm giá" type="text" name="coupon" id="couponInput" />
                                    </div>
                                    <button type="button" id="applyCouponBtn"
                                        class="h-full px-8 bg-[#4bf425] text-black font-black uppercase tracking-widest retro-border border-l-4 hover:bg-[#4bf425]/90 active:bg-[#4bf425]/80 transition-colors">
                                        Execute
                                    </button>
                                @endif
                            </form>
                        </div>
                        <div id="couponMessage" class="mt-2 text-sm font-bold text-[#FF5252]"></div>
                    </div>
                </div>

                <div class="w-full lg:w-[400px] flex-shrink-0">
                    <div class="bg-[#282A3A] retro-border shadow-retro p-8 flex flex-col gap-6 sticky top-32">
                        <h2
                            class="text-[#4bf425] text-2xl font-black uppercase tracking-wider border-b-4 border-black pb-4 flex items-center gap-3">
                            <i class="fas fa-shield-alt"></i>
                            Thông tin thanh toán
                        </h2>
                        <div class="flex flex-col gap-4 text-lg font-bold">
                            <div class="flex justify-between items-center text-slate-100">
                                <span>Tạm tính</span>
                                <span id="subTotalValue" class="text-[#FFEB3B]">${{ $subTotal }}</span>
                            </div>

                            <div id="totalDiscountItem" class="flex justify-between items-center text-[#FF5252]"
                                style="display: none !important;">
                                <span>Giảm giá</span>
                                <span id="totalDiscount">-$0.00</span>
                            </div>

                            @if (session()->get('coupon'))
                                <div class="flex justify-between items-center text-[#FF5252]">
                                    <span>Giảm giá</span>
                                    <span id="totalDiscount">-${{ session()->get('coupon') ?? '0.00' }}</span>
                                </div>
                            @endif

                            <div class="w-full h-1 bg-black my-2"></div>

                            <div class="flex justify-between items-center text-[#4bf425] text-2xl font-black">
                                <span>Tổng cộng</span>
                                <span id="totalAmount" class="text-[#FFEB3B] drop-shadow-md">
                                    @if (session()->get('coupon'))
                                        ${{ $subTotal - session()->get('coupon') }}
                                    @else
                                        ${{ $subTotal }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <a href="{{ route('checkout') }}"
                            class="mt-4 w-full h-16 bg-[#4bf425] text-black text-xl font-black uppercase tracking-widest retro-border shadow-retro shadow-retro-hover hover:translate-y-[2px] hover:translate-x-[2px] active:bg-[#4bf425]/80 transition-all flex items-center justify-center gap-3 group">
                            <i class="fas fa-power-off group-hover:animate-pulse"></i>
                            Thanh toán
                        </a>
                    </div>
                </div>
            </div>



            <script>
                $(document).ready(function() {
                    $('#applyCouponBtn').click(function() {
                        let formData = $('#couponForm').serialize(); // Serialize form data

                        $.ajax({
                            url: "/apply-coupon", // Replace with your route name
                            type: "POST",
                            data: formData,
                            success: function(response) {
                                // Calculate total discount
                                let totalDiscount = response.discounts.reduce((sum, item) => {
                                    return sum + parseFloat(item
                                        .discount); // Summing up discounts
                                }, 0);

                                // Update the Discount Amount
                                $('#totalDiscount').text(
                                    `$${totalDiscount.toFixed(2)}`); // Show the total discount amount
                                $('#totalDiscountItem').show(); // Show the discount item

                                // Update the Total Price after applying the discount
                                let subTotal = parseFloat("{{ $subTotal }}");
                                let totalAmount = subTotal - totalDiscount;
                                $('#totalAmount').text(
                                    `$${totalAmount.toFixed(2)}`); // Show the updated total

                                // Hide the Coupon Form
                                $('#couponForm')
                                    .hide(); // Hide the coupon area after successful application

                                // Success Toast Notification
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
                                    // Parse validation errors
                                    let errors = xhr.responseJSON.errors;
                                    let errorMessage = '';

                                    // Construct the error message
                                    for (let field in errors) {
                                        errorMessage += errors[field].join('<br>') + '<br>';
                                    }

                                    // Display the error messages in a Swal alert
                                    Swal.fire({
                                        position: 'top-end',
                                        title: 'Validation Errors',
                                        html: errorMessage,
                                        icon: 'error',
                                        toast: true, // Disable toast for detailed errors
                                        timer: 3000,
                                        showConfirmButton: false,
                                        background: '#dc3545',
                                        color: '#fff'
                                    });
                                } else {
                                    // Generic error message for other errors
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'error',
                                        title: 'Áp dụng mã giảm giá thất bại!',
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
                });
            </script>
