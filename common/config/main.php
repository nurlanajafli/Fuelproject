<?php
$modules = [];
if (YII_DEBUG) {
    $modules['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => ['*']
    ];
    $modules['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => ['*'],
        //'allowedIPs' => ['192.168.*.*', '172.*'],
        'generators' => [
            'giiant-crud' => [
                'class'     => 'schmunk42\giiant\generators\crud\Generator',
                'tidyOutput' => true,
                'actionButtonColumnPosition' => 'right',
                'templates' => [
                    'FE-default' => '@common/gii-templates/crud/fe-default'
                ]
            ]
        ],
    ];
}
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => $modules,
    'timeZone' => 'America/New_York', # 'Asia/Tashkent',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'pcmiler' => [
            'class' => 'common\components\PCMiler'
        ],
        'firebase' => [
            'class' => 'common\components\Firebase',
            'credentialsPath' => dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'firebase_credentials.json'
        ],
        'transaction' => [
            'class' => 'common\components\Transaction'
        ],
        'formatter' => [
            'dateFormat' => 'MM/dd/yy',
            'timeFormat' => 'HH:mm',
            'numberFormatterOptions' => [
                NumberFormatter::MIN_FRACTION_DIGITS => 2
            ],
            'nullDisplay' => 0
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages',
                    'sourceLanguage' => 'en',
                    'fileMap' => [
                    ]
                ]
            ]
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager'
        ]
    ],
];
