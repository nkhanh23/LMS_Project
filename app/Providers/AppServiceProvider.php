<?php

namespace App\Providers;

use App\Models\SiteInfo;
use App\Services\Contracts\AIProviderInterface;
use App\Services\GeminiChatService;
use App\Services\GeminiConfigService;
use App\Services\GeminiProviderService;
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
        $this->app->bind(AIProviderInterface::class, GeminiProviderService::class);

        $this->app->singleton(GeminiConfigService::class);
        $this->app->singleton(GeminiProviderService::class);
        $this->app->singleton(GeminiChatService::class);
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
