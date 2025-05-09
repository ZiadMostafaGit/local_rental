<?php

namespace App\Providers;

use App\Models\Review;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        config(['app.timezone' => 'Africa/Cairo']);
        date_default_timezone_set('Africa/Cairo');
        Carbon::setLocale('ar');

         View::composer('admin_dashboard.layout.pages-layout', function ($view) {
        $topReviews = Review::with('customer')->orderByDesc('rating')->take(3)->get();
        $view->with('topReviews', $topReviews);
    });
    }
}
