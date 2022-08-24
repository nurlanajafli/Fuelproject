<?php

$routes = [
    'POST oauth2/<action:\w+>' => 'rest/<action>',

    'GET /swagger/doc' => 'swagger/doc',
    'GET /swagger/api' => 'swagger/api',

    'POST /device' => 'device/create',
    'DELETE /device/<id:\w+>' => 'device/delete',
    'PATCH /device/<id:\w+>/attach' => 'device/attach',

    'GET /truck' => 'truck/index',

    'GET /trailer' => 'trailer/index',

    'GET /driver' => 'driver/index',

    'POST /report' => 'report/create',
    'GET /report' => 'report/index',
    'GET /report/<id:\d+>' => 'report/show',
    'POST /report/<id:\d+>/sign' => 'report/sign',
    'DELETE /report/<id:\d+>' => 'report/delete',

    'POST /report/<id:\d+>/media' => 'report-media/create',

    'GET /damage-type' => 'damage-type/index',

    'GET /report-flag' => 'report-flag/index',

    'GET /media-category' => 'report-media-category/index',
    'POST /media-category' => 'report-media-category/create',
    'PATCH /media-category/<id:\d+>' => 'report-media-category/update',
    'DELETE /media-category/<id:\d+>' => 'report-media-category/delete',
];

return $routes;