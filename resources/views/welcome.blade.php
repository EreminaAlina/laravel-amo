<?php

const TOKEN_FILE = 'token_info.json';

use AmoCRM\Client\AmoCRMApiClient;
use App\Models\amoCRM;
use STS\JWT\Facades\JWT;

$output = new \Symfony\Component\Console\Output\ConsoleOutput();


session_start();
$client_id = "013864f5-0431-416e-b179-ee2751ae8606";
$apiClient = new AmoCRMApiClient($client_id, 'i8ck0WS06Q7pJ7VZGoG11Ee8FZOphYAw1KWAFKE69Tm9AS1AEptwaVAKkx0zqYgA', 'http://localhost:8001/');

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
        $account_id = JWT::parse($accessToken->getToken())->get("account_id");
//        $output->writeln();
        if (!$accessToken->hasExpired()) {
            saveToken([
                'id' => $account_id,
                'accessToken' => $accessToken->getToken(),
                'refreshToken' => $accessToken->getRefreshToken(),
                'expires' => $accessToken->getExpires(),
                'baseDomain' => $apiClient->getAccountBaseDomain(),
            ]);
        } else {
            printf("Token is expired, reload page");
        }
    } catch (Exception $e) {
        die((string)$e);
    }
}


function saveToken($accessToken)
{
    if (
        isset($accessToken['id'])
        && isset($accessToken['accessToken'])
        && isset($accessToken['refreshToken'])
        && isset($accessToken['expires'])
        && isset($accessToken['baseDomain'])
    ) {
        amoCRM::createClient($accessToken);
    } else {
        exit('Invalid access token ' . var_export($accessToken, true));
    }
}
