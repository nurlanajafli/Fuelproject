<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => sprintf(
                'pgsql:host=%s;port=5432;dbname=%s;user=%s;password=%s',
                getenv('DATABASE_HOST'),
                getenv('DATABASE_DB'),
                getenv('DATABASE_USER'),
                getenv('DATABASE_PASSWORD'),
            ),
            'charset' => 'utf8',
        ],
        'cache' => [
            'class' => 'yii\redis\Cache',
            'redis' => [
                'hostname' => getenv('REDIS_HOST'),
                'port' => 6379,
                'database' => 0,
            ]
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            //'useFileTransport' => true,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'port' => 587,
                'encryption' => 'tls',
                'username' => 'trspublicacc@gmail.com',
                'password' => 'cZz=ewQ1!80',
            ],
            'messageConfig' => ['from' => 'trspublicacc@gmail.com']
        ],
        'pcmiler' => [
            'apiKey' => getenv('PCMILER_KEY'),
            'cacheDuration' => 259200,
        ],
        'samsara' => [
            'class' => '\common\components\Samsara',
            'apiKey' => getenv('SAMSARA_KEY'),
        ]
    ],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['192.168.*.*', '172.*'],
            'generators' => [
                'giiant-crud' => [
                    'class' => 'schmunk42\giiant\generators\crud\Generator',
                    'tidyOutput' => true,
                    'actionButtonColumnPosition' => 'right',
                    'templates' => [
                        'FE-default' => '@common/gii-templates/crud/fe-default'
                    ]
                ]
            ],
        ],
    ]
];
