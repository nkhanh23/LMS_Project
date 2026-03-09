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
        // Fetch Stripe settings from the database
        $stripeConfig = Striipe::first();
        if ($stripeConfig) {
            Config::set('stripe.stripe_pk', $stripeConfig->publish_key);
            Config::set('stripe.stripe_sk', $stripeConfig->secret_key);
        }
    }
}
