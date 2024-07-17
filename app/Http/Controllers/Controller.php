<?php

namespace App\Http\Controllers;

use AmoCRM\Client\AmoCRMApiClient;
use App\Models\AmoCRM;
use App\Models\Leads;
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

    public function signin(Request $request)
    {
        $params = $request->all();

        $accessToken = $this->amoCRMApiClient->getOAuthClient()->getAccessTokenByCode($params['code']);
        $this->amoCRMApiClient->setAccessToken($accessToken);

        $authData = [
            'client_id' => $params['client_id'],
            'client_secret' => env('AMOCRM_CLIENT_SECRET'),
            'subdomain' => env('AMOCRM_SUBDOMAIN'),
            'access_token' => $accessToken->getToken(),
            'redirect_uri' => env('AMOCRM_REDIRECT_URI'),
            'token_type' => $accessToken->getValues()['token_type'],
            'refresh_token' => $accessToken->getRefreshToken(),
            'expires_in' => time() + $accessToken->getExpires(),
        ];
        AmoCRM::create($authData);

        return response(['OK'], 200);
    }

    public function signout(Request $request){
        $params = $request->all();

        AmoCRM::where("client_id", $params['client_uuid'])->delete();
        Leads::query()->delete();

        return response(['OK'], 200);
    }
}
