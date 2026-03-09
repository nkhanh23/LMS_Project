<?php

namespace App\Repositories;

use Stripe\StripeClient;

class StripeRepository
{
    public function handlePayment(array $data)
    {
        $stripe = new StripeClient(config('stripe.stripe_sk'));

        // Prepare line items for Stripe Checkout
        $lineItems = [];
        $totalItemsPrice = array_sum($data['course_price']);
        $finalTotal = (int)$data['total_price'];
        
        // Calculate the ratio to adjust each item's price
        $discountRatio = $totalItemsPrice > 0 ? $finalTotal / $totalItemsPrice : 1;
        $runningTotal = 0;
        $count = count($data['course_id']);

        foreach ($data['course_id'] as $index => $courseId) {
            $originalPrice = (int)$data['course_price'][$index];
            // Proportional price
            $adjustedPrice = (int)round($originalPrice * $discountRatio);
            
            // Adjust the last item to ensure the sum is exactly equal to finalTotal
            if ($index === $count - 1) {
                $adjustedPrice = $finalTotal - $runningTotal;
            }
            $runningTotal += $adjustedPrice;

            $lineItems[] = [
                'price_data' => [
                    'currency' => 'vnd',
                    'product_data' => [
                        'name' => $data['course_name'][$index],
                        'images' => [isset($data['course_image'][$index]) ? $data['course_image'][$index] : ''],
                    ],
                    'unit_amount' => $adjustedPrice,
                ],
                'quantity' => 1,
            ];
        }

        // Create a Stripe Checkout session
        $session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
            'customer_email' => $data['email'],
        ]);

        return redirect($session->url);
    }
}
