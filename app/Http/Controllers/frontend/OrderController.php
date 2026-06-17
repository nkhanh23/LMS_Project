<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Services\EnrollmentService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Course;
use App\Repositories\VnPayRepository;


class OrderController extends Controller
{
    protected $paymentService;
    protected $enrollmentService;
    protected $vnPayRepository;

    public function __construct(PaymentService $paymentService, EnrollmentService $enrollmentService, VnPayRepository $vnPayRepository)
    {
        $this->paymentService = $paymentService;
        $this->enrollmentService = $enrollmentService;
        $this->vnPayRepository = $vnPayRepository;
    }

    public function order(OrderRequest $request)
    {
        // xác thực giá tiền từ database
        $verifiedData = $this->getVerifiedOrderData($request->course_id, $request->validated());

        if ($verifiedData['payment_type'] === 'stripe' && $verifiedData['total_price'] < 15000) {
            return redirect()->back()->with('error', 'Số tiền thanh toán qua Stripe tối thiểu là 15,000 đ (khoảng 0.50 USD). Vui lòng chọn phương thức thanh toán khác.');
        }

        // lưu dữ liệu đã xác thực vào session để lát sauy khi thanh toán thành công lấy ra đối chiếu
        session()->put('stripe_data', $verifiedData);

        try {
            return $this->paymentService->processPayment($verifiedData);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xử lý thanh toán: ' . $e->getMessage());
        }
    }


    public function success(Request $request)
    {
        //lấy mã session_id mà Stripe gửi về trên URL
        $sessionId = $request->query('session_id');
        $stripe = new StripeClient(config('stripe.stripe_sk'));

        try {
            // gọi thẳng lên API Stripe kiểm tra xem sessionId này đã thực sự thu tiền thành công chưa
            $session = $stripe->checkout->sessions->retrieve($sessionId);
            // lấy thông tin thanh toán
            $paymentIntent = $stripe->paymentIntents->retrieve($session->payment_intent);

            DB::transaction(function () use ($session, $paymentIntent) {
                // lưu lại Log Thanh toán (Payment) có mã transaction của Stripe
                $payment = $this->createPayment($session, $paymentIntent);
                // Kích xuất thông tin Đơn Hàng bằng biến Session `stripe_data` lưu ở bước đầu tiên
                $orders = $this->createOrder($payment->id);
                //Mỗi khoá học trong Đơn Hàng, gọi EnrollmentService "cấp quyền học viên" (Enrolled) cho khoá đó
                foreach ($orders as $order) {
                    $this->enrollmentService->grantFromOrder($order);
                }
            });

            // Xoá giỏ hàng sau khi thanh toán thành công
            $guestToken = $request->cookie('guest_token') ?? Str::uuid();
            Cart::where('guest_token', $guestToken)->delete();

            return redirect('/')->with('success', 'Đặt hàng thành công');
        } catch (\Exception $e) {
            return redirect('/checkout')->with('error', $e->getMessage());
        }
    }

    public function cancel()
    {
        return view('frontend.pages.checkout.stripe.cancel');
    }

    private function createPayment($session, $paymentIntent): Payment
    {
        return Payment::create([
            'transaction_id' => $paymentIntent->id,
            'name' => $session->customer_details->name,
            'email' => $session->customer_details->email,
            'phone' => $session->customer_details->phone,
            'total_amount' => $session->amount_total,
            'payment_type' => 'stripe',
            'invoice_no' => 'INV-' . strtoupper((uniqid())),
            'order_date' => now()->toDateString(),
            'order_month' => now()->month,
            'order_year' => now()->year,
            'status' => 'completed',
        ]);
    }

    private function createOrder($paymentId)
    {
        $stripeData = session('stripe_data');
        $orders = collect();

        foreach ($stripeData['course_id'] as $index => $courseId) {
            $orders->push(Order::create([
                'payment_id' => $paymentId,
                'user_id' => Auth::id(),
                'course_id' => $courseId,
                'instructor_id' => $stripeData['instructor_id'][$index],
                'course_title' => $stripeData['course_name'][$index],
                'price' => $stripeData['course_price'][$index],
                'gross_amount' => $stripeData['course_price'][$index],
                'platform_amount' => 0,
                'net_amount' => $stripeData['course_price'][$index],
                'status' => 'completed',
                'paid_at' => now(),
            ]));
        }

        return $orders;
    }

    public function vnpayPayment(Request $request)
    {
        $courseIds = $request->input('course_id');
        if (!$courseIds || !is_array($courseIds)) {
            return redirect()->back()->with('error', 'Thông tin khóa học không hợp lệ.');
        }

        // 1. Re-calculate and verify all prices from Database to prevent tampering
        $verifiedData = $this->getVerifiedOrderData($courseIds, $request->all());

        // 2. Lưu thông tin đơn hàng "SẠCH" vào Session
        session()->put('stripe_data', $verifiedData);
        session()->save();

        // 3. Lấy cấu hình VnPay
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_Url = config('vnpay.vnp_Url');
        $vnp_Returnurl = config('vnpay.vnp_Returnurl');

        // 4. Thông tin đơn hàng
        $vnp_TxnRef = 'INV-' . strtoupper(uniqid());
        $vnp_OrderInfo = "Thanh toan don hang khoa hoc StackLearn - " . $vnp_TxnRef;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $verifiedData['total_price'] * 100; // Sử dụng giá đã được xác thực từ Server

        $vnp_Locale = 'vn';
        $vnp_IpAddr = $request->ip();

        // 4. Xây dựng mảng dữ liệu (Input Data)
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        // 5. Sắp xếp dữ liệu theo thứ tự Alphabet
        ksort($inputData);

        $query = "";
        $i = 0;
        $hashdata = "";

        // 6. Chuẩn hóa chuỗi dữ liệu
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;

        // 7. Tạo Secure Hash
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        // 8. Chuyển hướng người dùng sang cổng VnPay
        return redirect()->away($vnp_Url);
    }

    public function vnpayReturn(Request $request)
    {
        $inputData = $request->all();

        // 1. Kiểm tra chữ ký bảo mật
        if (!$this->vnPayRepository->verifyResponse($inputData)) {
            return redirect()->route('checkout')->with('error', 'Chữ ký thanh toán không hợp lệ');
        }

        // 2. Kiểm tra session data có tồn tại không
        $stripeData = session('stripe_data');
        if (!$stripeData || !isset($stripeData['course_id'])) {
            return redirect()->route('checkout')->with('error', 'Phiên thanh toán đã hết hạn. Vui lòng thử lại.');
        }

        // 3. Kiểm tra mã phản hồi (00 là thành công)
        if ($inputData['vnp_ResponseCode'] == '00') {
            try {
                DB::transaction(function () use ($inputData) {
                    // 4. Lưu thông tin Payment
                    $payment = Payment::create([
                        'transaction_id' => $inputData['vnp_TransactionNo'],
                        'name'           => Auth::user()->name,
                        'email'          => Auth::user()->email,
                        'phone'          => Auth::user()->phone,
                        'total_amount'   => $inputData['vnp_Amount'] / 100,
                        'payment_type'   => 'vnpay',
                        'invoice_no'     => $inputData['vnp_TxnRef'],
                        'order_date'     => now()->toDateString(),
                        'order_month'    => now()->month,
                        'order_year'     => now()->year,
                        'status'         => 'completed',
                        'provider_payload' => json_encode($inputData),
                    ]);

                    // 5. Tạo chi tiết đơn hàng (Orders) từ Session
                    $orders = $this->createOrder($payment->id);

                    // 6. Cấp quyền truy cập khóa học
                    foreach ($orders as $order) {
                        $this->enrollmentService->grantFromOrder($order);
                    }
                });

                // Xóa giỏ hàng
                $guestToken = $request->cookie('guest_token') ?? Str::uuid();
                Cart::where('guest_token', $guestToken)->delete();

                session()->forget('stripe_data');

                return redirect('/')->with('success', 'Thanh toán qua VnPay thành công!');
            } catch (\Exception $e) {
                return redirect('/checkout')->with('error', 'Lỗi hệ thống: ' . $e->getMessage());
            }
        }

        return redirect()->route('checkout')->with('error', 'Giao dịch thất bại hoặc bị hủy');
    }

    /**
     * Xác thực thông tin đơn hàng bằng cách lấy giá từ Database
     */
    private function getVerifiedOrderData(array $courseIds, array $originalData)
    {
        $courses = Course::whereIn('id', $courseIds)->get();
        $totalPrice = 0;

        $courseNames = [];
        $coursePrices = [];
        $instructorIds = [];

        // Duyệt qua từng ID khóa học để đảm bảo tính đúng thứ tự và số lượng (nếu mua trùng - mặc dù giỏ hàng thường unique)
        foreach ($courseIds as $id) {
            $course = $courses->firstWhere('id', $id);
            if ($course) {
                // Ưu tiên discount_price nếu có, nếu không lấy selling_price
                $price = ($course->discount_price && $course->discount_price > 0)
                    ? $course->discount_price
                    : $course->selling_price;

                $totalPrice += $price;

                $courseNames[] = $course->course_name;
                $coursePrices[] = $price;
                $instructorIds[] = $course->instructor_id;
            }
        }

        // Ghi đè các thông tin nhạy cảm bằng dữ liệu đã xác thực
        return array_merge($originalData, [
            'course_name' => $courseNames,
            'course_price' => $coursePrices,
            'instructor_id' => $instructorIds,
            'total_price' => $totalPrice
        ]);
    }
}
