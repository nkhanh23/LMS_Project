<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\StripeClient;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function order(OrderRequest $request)
    {
        session()->put('stripe_data', $request->validated());
        return $this->paymentService->processPayment($request->validated());
    }

    public function success(Request $request)
    {
        //Lấy session_id từ query string
        $sessionId = $request->query('session_id');
        $stripe = new StripeClient(config('stripe.stripe_sk'));
        try {
            //Lấy thông tin session từ Stripe
            $session = $stripe->checkout->sessions->retrieve($sessionId);
            $paymentIntent = $stripe->paymentIntents->retrieve($session->payment_intent);

            $this->createPayment($session, $paymentIntent);

            //xóa dữ liệu cart
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

    private function createPayment($session, $paymentIntent)
    {
        //Tạo payment sử dụng thông tin từ session và paymentIntent
        $payment = Payment::create([
            'transaction_id' => $paymentIntent->id,
            'name' => $session->customer_details->name,
            'email' => $session->customer_details->email,
            'phone' => $session->customer_details->phone,
            'total_amount' => $session->amount_total, // No / 100 for VND
            'payment_type' => 'stripe',
            'invoice_no' => 'INV-' . strtoupper((uniqid())),
            'order_date' => now()->toDateString(),
            'order_month' => now()->month,
            'order_year' => now()->year,
            'status' => 'completed',
        ]);

        $this->createOrder($payment->id);
    }

    private function createOrder($paymentId)
    {
        //lấy lại dữ liệu từ session
        $stripeData = session('stripe_data');
        // Tạo order cho mỗi khóa học
        foreach ($stripeData['course_id'] as $index => $courseId) {
            Order::create([
                'payment_id' => $paymentId, // Associate with the created payment record
                'user_id' => Auth::user()->id, // Assuming user is authenticated
                'course_id' => $courseId,
                'instructor_id' => $stripeData['instructor_id'][$index], // Add logic to retrieve instructor ID if needed
                'course_title' => $stripeData['course_name'][$index],
                'price' => $stripeData['course_price'][$index],
                'gross_amount'    => $stripeData['course_price'][$index],
                'platform_amount' => 0,
                'net_amount'      => $stripeData['course_price'][$index],
                'status' => 'completed',
                'paid_at' => now(),
            ]);
        }
    }
}
