<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        $this->loadInternalHelpers();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    protected function loadInternalHelpers() {

        $helper_path = base_path() . '/helpers/*.php';

        foreach (glob(__DIR__.'/../helpers/*.php') as $filename) {

            require_once $filename;
        }
    }


}
