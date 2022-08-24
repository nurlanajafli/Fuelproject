<?php

use yii\grid\ActionColumn;
use yii\helpers\Html;

// error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

$actionColumnSetting = [
    'buttons' => [
        'view' => function($name, $model, $key){
            return Html::a('<i class="fas fa-eye" aria-hidden="true"></i>', ['view']);
        },
        'update' => function($name, $model, $key){
            return Html::a('<i class="fas fa-pencil-alt" aria-hidden="true"></i>', ['update']);
        },
        'delete' => function($name, $model, $key){
            return Html::a('<i class="fas fa-trash" aria-hidden="true"></i>', ['delete']);
        }
    ],
];
\Yii::$container->set(ActionColumn::class, $actionColumnSetting);