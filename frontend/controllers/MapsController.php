<?php

namespace frontend\controllers;

use common\controllers\MapsController as BaseMapsController;

class MapsController extends BaseMapsController
{
    protected function accessRules()
    {
        return [
            [
                'actions' => ['search'],
                'allow' => true,
                'roles' => ['?', '@']
            ]
        ];
    }
}