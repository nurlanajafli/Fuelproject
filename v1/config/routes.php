<?php

$routes = [
    'POST oauth2/<action:\w+>' => 'rest/<action>',
    //'POST oauth2/<action:\w+>' => 'oauth2/rest/<action>',

    'GET /swagger/doc' => 'swagger/doc',
    'GET /swagger/api' => 'swagger/api',

    'GET /load/dispatch' => 'load/dispatch',
    'GET /load/history' => 'load/history',
    'GET /load/reserved' => 'load/reserved',
    'GET /load/<id:\d+>' => 'load/show',
    'POST /load/<id:\d+>/upload' => 'load/upload',

    'GET /load-stop/<id:\d+>' => 'load-stop/show',
    'PATCH /load-stop/<id:\d+>/arrive' => 'load-stop/arrive',
    'PATCH /load-stop/<id:\d+>/depart' => 'load-stop/depart',

    'GET /chat-message' => 'chat-message/index',
    'POST /chat-message' => 'chat-message/create',
    'PATCH /chat-message' => 'chat-message/update',
    'PATCH /chat-message/<id:\d+>/read' => 'chat-message/read',
    'GET /chat-channel' => 'chat-channel/index',

    'GET /document-code' => 'document-code/index',

    'POST /device' => 'device/create',
    'DELETE /device/<id:\w+>' => 'device/delete',
    'PATCH /device/<id:\w+>/attach' => 'device/attach',
];

return $routes;