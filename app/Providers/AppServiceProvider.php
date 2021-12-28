<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        \Config::set('mail.driver', 'ses');
        \Config::set('mail.host', 'email-smtp.ap-south-1.amazonaws.com');
        \Config::set('mail.port', '587');
        \Config::set('mail.username', 'AKIAWNA2PIA3VZ7DN64Q');
        \Config::set('mail.password', 'F4Aqk8mhmY/BaI5UymBhRykRc3uHQ7P1Mcxux55Q');
        \Config::set('mail.encryption', 'tls');
//        \URL::forceScheme('https');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
