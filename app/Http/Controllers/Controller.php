<?php

namespace App\Http\Controllers;

use AmoCRM\Client\AmoCRMApiClient;
use App\Models\AmoCRM;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    private AmoCRMApiClient $amoCRMApiClient;

    public function __construct(AmoCRMApiClient $amoCRMApiClient)
    {
        $this->amoCRMApiClient = $amoCRMApiClient;
    }

    public function auth(Request $request)
    {
        $params = $request->all();

        $accessToken = $this->amoCRMApiClient->getOAuthClient()->getAccessTokenByCode($params['code']);
        $this->amoCRMApiClient->setAccessToken($accessToken);

        $authData = [
            'client_id' => $params['client_id'],
            'client_secret' => 'i8ck0WS06Q7pJ7VZGoG11Ee8FZOphYAw1KWAFKE69Tm9AS1AEptwaVAKkx0zqYgA',
            'subdomain' => 'itweltintegration.amocrm.ru',
            'access_token' => $accessToken->getToken(),
            'redirect_uri' => 'https://51bc-81-23-165-131.ngrok-free.app/api/auth',
            'token_type' => $accessToken->getValues()['token_type'],
            'refresh_token' => $accessToken->getRefreshToken(),
            'expires_in' => time() + $accessToken->getExpires(),
        ];
        AmoCRM::create($authData);

        return response(['OK'], 200);
    }
}
