<?php

namespace backend\controllers;

use backend\controllers\base\BaseController;
use common\models\Setting;
use Yii;

class SettingsController extends BaseController
{
    public function allowedAttributes()
    {
        $formName = (new Setting())->formName();
        return [
            'update' => [
                $formName => [['key', 'value']]
            ]
        ];
    }

    public function requiredAttributes()
    {
        $formName = (new Setting())->formName();
        return [
            'update' => [
                $formName => [['key', 'value']]
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index', ['map' => Setting::map()]);
    }

    public function actionUpdate()
    {
        $result = Yii::$app->transaction->exec(function () {
            $formName = (new Setting())->formName();
            $models = $this->getAllowedPost();
            foreach ($models[$formName] as $model) {
                $setting = Setting::findOne($model['key']);
                if (!$setting) {
                    $setting = new Setting();
                }
                $setting->load([$formName => $model]);
                if (!$this->saveModel($setting)) {
                    return false;
                }
            }
            return true;
        });
        if ($result)
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Saved'));
        return $this->redirect(['index']);
    }
}
