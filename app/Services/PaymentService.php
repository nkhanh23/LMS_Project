<?php

namespace App\Services;

use App\Repositories\StripeRepository;
use App\Repositories\VnPayRepository;

class PaymentService
{
    protected $stripeRepository, $vnPayRepository;

    public function __construct(StripeRepository $stripeRepository, VnPayRepository $vnPayRepository)
    {
        $this->stripeRepository = $stripeRepository;
        $this->vnPayRepository = $vnPayRepository;
    }

    public function processPayment(array $data)
    {
        switch ($data['payment_type']) {
            case 'stripe':
                return $this->stripeRepository->handlePayment($data);

            case 'paypal':
                return "paypal";

            case 'vnpay':
                // Thêm invoice_no tạm thời để VnPay định danh giao dịch
                $data['invoice_no'] = 'VNP' . strtoupper(uniqid());
                $url = $this->vnPayRepository->generatePaymentUrl($data);
                return redirect($url);

            default:
                throw new \Exception('Không hỗ trợ');
        }
    }
}
