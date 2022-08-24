<?php

namespace garage\tests\helpers;

use common\enums\GrantType;
use common\models\User;
use filsh\yii2\oauth2server\models\OauthClients;
use yii\helpers\Json;

class Oauth2 extends \Codeception\Module
{
    public function authorize()
    {
        $DataFactory = $this->getModule('DataFactory');
        $REST = $this->getModule('REST');
        $user = $DataFactory->have(User::class);
        $client = $DataFactory->have(OauthClients::class);
        $REST->sendPost('/oauth2/token', [
            'grant_type' => GrantType::PASSWORD,
            'client_id' => $client->client_id,
            'client_secret' => $client->client_secret,
            'username' => $user->email,
            'password' => 'password',
        ]);
        $resp = Json::decode($REST->grabResponse());
        $REST->amBearerAuthenticated($resp['access_token']);
    }
}
