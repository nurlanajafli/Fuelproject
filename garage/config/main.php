<?php

use filsh\yii2\oauth2server\Module;
use garage\components\oauth2\AppIdentity;
use garage\components\oauth2\PublicKeyStorage;
use garage\components\oauth2\User;
use garage\components\oauth2\UserCredentials;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\RefreshToken;
use OAuth2\Storage\JwtAccessToken;

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);
return [
    'id' => 'app-garage',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'garage\controllers',
    'defaultRoute' => 'swagger/index',
    'bootstrap' => ['oauth2'],
    'modules' => [
        'oauth2' => [
            'class' => Module::class,
            'tokenParamName' => 'accessToken',
            'tokenAccessLifetime' => 3600 * 24,
            'useJwtToken' => true,
            'storageMap' => [
                'user_credentials' => UserCredentials::class,
                'public_key' => PublicKeyStorage::class,
                'access_token' => JwtAccessToken::class
            ],
            'grantTypes' => [
                'user_credentials' => [
                    'class' => \OAuth2\GrantType\UserCredentials::class
                ],
                'client_credentials' => [
                    'class' => ClientCredentials::class,
                    'allow_public_clients' => false,
                    'allow_credentials_in_request_body' => false
                ],
                'refresh_token' => [
                    'class' => RefreshToken::class,
                    'always_issue_new_refresh_token' => false,
                    'unset_refresh_token_after_use' => false
                ]
            ]
        ]
    ],
    'components' => [
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'errorHandler' => [
            'class' => \garage\components\ErrorHandler::class,
        ],
        'user' => [
            'class' => User::class,
            'identityClass' => AppIdentity::class,
        ],
        /*'authClientCollection' => [
            'class' => \yii\authclient\Collection::class,
            'clients' => []
        ],*/
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => require(__DIR__ . '/routes.php'),
        ],
        'formatter' => [
            'timeZone' => 'UTC',
            'datetimeFormat' => $params['formats']['ISO8601'],
        ],
    ],
    'params' => $params
];
