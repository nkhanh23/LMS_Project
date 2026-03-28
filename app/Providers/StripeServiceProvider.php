<?php

namespace App\Providers;

use App\Models\Striipe;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Stripe\Stripe;

class StripeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Tìm record cấu hình stripe mới nhất trong DB
        $stripeConfig = Striipe::first();
        if ($stripeConfig) {
            // Set config stripe
            Config::set('stripe.stripe_pk', $stripeConfig->publish_key);
            Config::set('stripe.stripe_sk', $stripeConfig->secret_key);
        }
    }
}
