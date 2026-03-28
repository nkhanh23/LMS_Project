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

class OrderController extends Controller
{
    protected $paymentService;
    protected $enrollmentService;
    public function __construct(PaymentService $paymentService, EnrollmentService $enrollmentService)
    {
        $this->paymentService = $paymentService;
        $this->enrollmentService = $enrollmentService;
    }

    public function order(OrderRequest $request)
    {
        // Đưa toàn bộ nội dung vừa đăng ký vào Session
        // Để lát nữa sau khi redirect từ Web Stripe về còn biết user đã mua những gì.
        session()->put('stripe_data', $request->validated());
        return $this->paymentService->processPayment($request->validated());
    }

    public function success(Request $request)
    {
        //lấy mã số mã kiểm soát do Stripe trả về trên URL
        $sessionId = $request->query('session_id');
        $stripe = new StripeClient(config('stripe.stripe_sk'));

        try {
            // Gọi thẳng lên API Stripe kiểm tra xem sessionId này đã thực sự thu tiền thành công chưa
            $session = $stripe->checkout->sessions->retrieve($sessionId);
            // Lấy thông tin thanh toán
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
}
