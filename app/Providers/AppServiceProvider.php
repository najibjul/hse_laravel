<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        if (auth()->check()) {
            $notifications =  Notification::where('to_user_id', auth()->user()->id)->get();
            $totalNotification = $notifications->count();

            View::composer('notification', function ($view) use($notifications) {
                $view->with('notifications', $notifications);    
            });

            View::composer('total-notification', function ($view) use($totalNotification) {
                $view->with('totalNotification', $totalNotification);    
            });
        } else {
            View::composer('notification', function ($view) {
                $view->with('notifications', []);
            });

            View::composer('total-notification', function ($view) {
                $view->with('totalNotification', 0);    
            });
        }
    }
}
