<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // メール認証済みユーザー判別のためのディレクティブ
        Blade::if('verified', function () {
            return auth()->check() && auth()->user()->hasVerifiedEmail();
        });
    }
}
