<?php

namespace console\controllers;


use common\enums\Permission;
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $permissions = Permission::getEnums();
        foreach ($permissions as $name) {
            $permission = $auth->createPermission($name);
            $auth->add($permission);
        }
    }
}