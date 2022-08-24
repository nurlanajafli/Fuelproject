<?php

namespace common\controllers;

use common\models\User;
use Yii;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

abstract class BaseController extends Controller
{
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::className(),
                    'rules' => $this->accessRules(),
                ],
            ]
        );
    }

    protected function accessRules()
    {
        return [
            ['allow' => true, 'roles' => ['@']],
        ];
    }

    public function afterAction($action, $result)
    {
        $result = parent::afterAction($action, $result);
        if (!User::checkSessionId()) {
            Yii::$app->user->logout();
            return $this->goHome();
        }

        return $result;
    }
}
