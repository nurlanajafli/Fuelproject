<?php

namespace backend\controllers;

use Yii;
use common\models\AccountingDefault;

class AccountingDefaultController extends \yii\web\Controller
{
    public function actionIndex()
    {
        /** @var AccountingDefault|null $model */
        $model = AccountingDefault::find()->one();
        if (is_null($model)) {
            $model = new AccountingDefault();
        }
        if ($model->load($_POST) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', Yii::t('app', 'Saved'));
            return $this->redirect('');
        }
        return $this->render('index', ['model' => $model]);
    }
}
