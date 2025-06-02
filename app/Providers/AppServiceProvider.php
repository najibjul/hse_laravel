<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
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
        View::composer(['layouts.app'], function ($view) {
            if (Auth::check()) {
                $notifications =  Notification::where('user_id', Auth::user()->id)->orderByDesc('id')->get();
                $totalNotification = $notifications->count();
                $view->with(['notifications'=> $notifications , 'totalNotification' => $totalNotification ]);

            } else {
                $view->with(['notifications'=> [] , 'totalNotification' => 0 ]);
            }
        });
    }
}
