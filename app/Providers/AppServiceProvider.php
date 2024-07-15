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
            $amoClient = new AmoCRMApiClient('013864f5-0431-416e-b179-ee2751ae8606', 'i8ck0WS06Q7pJ7VZGoG11Ee8FZOphYAw1KWAFKE69Tm9AS1AEptwaVAKkx0zqYgA', 'https://1faf-81-23-165-131.ngrok-free.app/api/auth');
            $amoClient->setAccountBaseDomain('itweltintegration.amocrm.ru');

            return $amoClient;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(AmoCRMApiClient $amoClient): void
    {
        $amoIntegration = AmoCRM::where('client_id', '013864f5-0431-416e-b179-ee2751ae8606')->first();

        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln(json_encode($amoIntegration));

        if ($amoIntegration) {
            $hui = [
                'access_token' => $amoIntegration->access_token,
                'refresh_token' => $amoIntegration->refresh_token,
                'resource_owner_id' => $amoIntegration->client_id,
                'expires_in'=> $amoIntegration->expires_in,
            ];
            $amoClient->setAccessToken(new AccessToken($hui));
        }
    }
}
