<?php

use common\enums\GrantType;
use filsh\yii2\oauth2server\models\OauthClients;
use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define(OauthClients::class)->setDefinitions([
    'client_id' => Faker::lexify(str_repeat('?', 32)),
    'client_secret' => Faker::lexify(str_repeat('?', 32)),
    'redirect_uri' => 'oob',
    'grant_types' => GrantType::CLIENT_CREDENTIALS . ' ' . GrantType::PASSWORD,
]);