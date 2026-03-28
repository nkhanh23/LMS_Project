<?php

namespace App\Providers;

use App\Models\SiteInfo;
use App\Services\GeminiChatService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GeminiChatService::class, function ($app) {
            return new GeminiChatService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $site_info = SiteInfo::first();
        View::share('site_info', $site_info);

        Paginator::useBootstrapFive();
    }
}
