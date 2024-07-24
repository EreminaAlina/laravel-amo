<?php

namespace App\Providers;

use AmoCRM\Client\AmoCRMApiClient;
use App\Models\AmoCRM;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use League\OAuth2\Client\Token\AccessToken;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AmoCRMApiClient::class, function (Application $app) {
            $amoClient = new AmoCRMApiClient('013864f5-0431-416e-b179-ee2751ae8606', env('AMOCRM_CLIENT_SECRET'), env('AMOCRM_REDIRECT_URI'));
            $amoClient->setAccountBaseDomain(env('AMOCRM_SUBDOMAIN'));
            $amoIntegration = AmoCRM::limit(1)->first();
            if ($amoIntegration) {

                $tokenData = [
                    'access_token' => $amoIntegration->access_token,
                    'refresh_token' => $amoIntegration->refresh_token,
                    'resource_owner_id' => $amoIntegration->client_id,
                    'expires_in'=> $amoIntegration->expires_in,
                ];
                $amoClient->setAccessToken(new AccessToken($tokenData));
            }
            return $amoClient;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
