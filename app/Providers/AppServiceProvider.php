<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Cache, App\Models\MerchantApp;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer(['frontend.partials.footer'], function ($view) {
            $games = Cache::rememberForever('active_game_list', function () {
                $_games = MerchantApp::where('status', 1)->get();
                return $_games->toArray();
            });

            $view->with('games', $games);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
