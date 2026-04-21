<?php

namespace App\Providers;

use App\Models\GeminiSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class GeminiConfigServiceProvider extends ServiceProvider
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
        // Lấy cấu hình Gemini từ database
        $setting = GeminiSetting::first();

        if ($setting) {
            Config::set('services.gemini.api_key', $setting->api_key);
            if ($setting->base_url) {
                Config::set('services.gemini.base_url', $setting->base_url);
            }
            Config::set('services.gemini.model', $setting->model_name);
            Config::set('services.gemini.timeout', $setting->timeout_seconds);
            Config::set('services.gemini.temperature', $setting->temperature);
            Config::set('services.gemini.max_output_tokens', $setting->max_output_tokens);
            Config::set('services.gemini.enabled', $setting->is_enabled);
        }
    }
}
