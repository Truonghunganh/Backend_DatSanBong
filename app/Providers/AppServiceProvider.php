<?php

namespace App\Providers;

use Illuminate\Contracts\View\View;

//use App\Setting;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public $checkdatsan=true;
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
        view()->share('key', true);
        // view()->composer('*', function (View $view) {
        //     $site_settings = Setting::all();
        //     $view->with('site_settings', $site_settings);
        // });
        
    }
}

