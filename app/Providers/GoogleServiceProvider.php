<?php

namespace App\Providers;

use App\Models\Google;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;

class GoogleServiceProvider extends ServiceProvider
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
        try {
            // Lấy cấu hình Google từ database
            $googleConfig = Google::first();

            if ($googleConfig) {
                Config::set('services.google.client_id', $googleConfig->client_id);
                Config::set('services.google.client_secret', $googleConfig->secret_key);
                Config::set('services.google.redirect', $googleConfig->redirect_uri ?? config('services.google.redirect'));
            }
        } catch (\Exception $e) {
            // Table may not exist yet
        }
    }
}
