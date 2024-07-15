<?php

namespace App\Providers;

use AmoCRM\Client\AmoCRMApiClient;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AmoCRMApiClient::class, function (Application $app) {
            $amoClient = new AmoCRMApiClient('013864f5-0431-416e-b179-ee2751ae8606', 'i8ck0WS06Q7pJ7VZGoG11Ee8FZOphYAw1KWAFKE69Tm9AS1AEptwaVAKkx0zqYgA', 'https://51bc-81-23-165-131.ngrok-free.app/api/auth');
            $amoClient->setAccountBaseDomain('itweltintegration.amocrm.ru');

            return $amoClient;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
