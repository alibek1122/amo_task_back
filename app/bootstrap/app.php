<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

use AmoCRM\Client\AmoCRMApiClient;
use App\Models\AuthData;
use League\OAuth2\Client\Token\AccessToken;

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/
$app->singleton(
    AmoCRMApiClient::class,
    function ($app) {
        $data = AuthData::where('client_id', env('ID'))->first();
        $amo = new AmoCRMApiClient(env('ID'), env('SECRET'), env('URL'));
        $amo->setAccountBaseDomain(env('DOMAIN'));
        if ($data === null) {
            $token = $amo->getOAuthClient()->getAccessTokenByCode(env('CODE'));
            $data = new AuthData(
                [
                    'client_id' => env('ID'),
                    'baseDomain' => $amo->getAccountBaseDomain(),
                    'refreshToken' => $token->getRefreshToken(),
                    'expires' => $token->getExpires(),
                    'accessToken' => $token->getToken()
                ]
            );
            $data->save();
            $amo->setAccessToken($token);
            return $amo;
        }
        $token = new AccessToken(
            [
                'baseDomain' => $data->baseDomain,
                'refresh_token' => $data->refreshToken,
                'expires' => $data->expires,
                'access_token' => $data->accessToken
            ]
        );
        $acToken = $amo->getOAuthClient()->getAccessTokenByRefreshToken($token);
        $amo->setAccessToken($acToken);
        $data->update(
            [
                'client_id' => env('ID'),
                'baseDomain' => $amo->getAccountBaseDomain(),
                'refreshToken' => $acToken->getRefreshToken(),
                'expires' => $acToken->getExpires(),
                'accessToken' => $acToken->getToken()
            ]
        );
        return $amo;
    }
);

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
