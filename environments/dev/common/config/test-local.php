<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => sprintf(
                'pgsql:host=database;port=5432;dbname=%s;user=%s;password=%s',
                getenv('TEST_DB_NAME'),
                getenv('TEST_DB_USER'),
                getenv('TEST_DB_PASSWORD'),
            ),
            'charset' => 'utf8',
        ],
    ],
];
