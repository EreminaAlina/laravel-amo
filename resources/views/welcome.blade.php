<?php

const TOKEN_FILE = 'token_info.json';

use AmoCRM\Client\AmoCRMApiClient;
use App\Models\Clients;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

session_start();

$apiClient = new AmoCRMApiClient("013864f5-0431-416e-b179-ee2751ae8606", 'i8ck0WS06Q7pJ7VZGoG11Ee8FZOphYAw1KWAFKE69Tm9AS1AEptwaVAKkx0zqYgA', 'http://localhost:8001/');

if (isset($_GET['referer'])) {
    $apiClient->setAccountBaseDomain($_GET['referer']);
}

if (!isset($_GET['request'])) {
    if (!isset($_GET['code'])) {
        $_SESSION['oauth2state'] = bin2hex(random_bytes(16));
        echo $apiClient->getOAuthClient()->getOAuthButton(
            [
                'title' => 'Установить интеграцию',
                'compact' => false,
                'class_name' => 'className',
                'color' => 'default',
                'mode' => 'popup',
                'error_callback' => 'handleOauthError',
                'state' => $_SESSION['oauth2state'],
            ]
        );
        echo '<script>
            handleOauthError = function(event) {
                alert(\'ID клиента - \' + event.client_id + \' Ошибка - \' + event.error);
            }
            </script>';

        die;
    } elseif (empty($_GET['state']) || empty($_SESSION['oauth2state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
        unset($_SESSION['oauth2state']);
        exit('Invalid state');
    }

    /**
     * Ловим обратный код
     */
    try {
        $accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($_GET['code']);

        if (!$accessToken->hasExpired()) {
            saveToken([
                'accessToken' => $accessToken->getToken(),
                'refreshToken' => $accessToken->getRefreshToken(),
                'expires' => $accessToken->getExpires(),
                'baseDomain' => $apiClient->getAccountBaseDomain(),
            ]);
        }
    } catch (Exception $e) {
        die((string)$e);
    }
} else {
    $accessToken = getToken();

    $apiClient->setAccessToken($accessToken)
        ->setAccountBaseDomain($accessToken->getValues()['baseDomain'])
        ->onAccessTokenRefresh(
            function (\League\OAuth2\Client\Token\AccessTokenInterface $accessToken, string $baseDomain) {
                saveToken(
                    [
                        'accessToken' => $accessToken->getToken(),
                        'refreshToken' => $accessToken->getRefreshToken(),
                        'expires' => $accessToken->getExpires(),
                        'baseDomain' => $baseDomain,
                    ]
                );
            });

}


function saveToken($accessToken)
{
    if (
        isset($accessToken)
        && isset($accessToken['accessToken'])
        && isset($accessToken['refreshToken'])
        && isset($accessToken['expires'])
        && isset($accessToken['baseDomain'])
    ) {
//        $data = {
//            accessToken: $accessToken['accessToken'],
//            refreshToken: $accessToken['refreshToken'],
//            expires: $accessToken['expires'],
//            baseDomain: $accessToken['baseDomain'],
//        }
        Clients::createClient($accessToken);

    } else {
        exit('Invalid access token ' . var_export($accessToken, true));
    }
}

/**
 * @return \League\OAuth2\Client\Token\AccessToken
 */
function getToken()
{
    $accessToken = Storage::json(TOKEN_FILE);

    if (
        isset($accessToken)
        && isset($accessToken['accessToken'])
        && isset($accessToken['refreshToken'])
        && isset($accessToken['expires'])
        && isset($accessToken['baseDomain'])
    ) {
        return new \League\OAuth2\Client\Token\AccessToken([
            'access_token' => $accessToken['accessToken'],
            'refresh_token' => $accessToken['refreshToken'],
            'expires' => $accessToken['expires'],
            'baseDomain' => $accessToken['baseDomain'],
        ]);
    } else {
        exit('Invalid access token ' . var_export($accessToken, true));
    }
}
