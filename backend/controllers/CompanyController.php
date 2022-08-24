<?php

namespace backend\controllers;

use Yii;
use yii\helpers\Url;
use common\models\CompanyBusinessDirection;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;
use common\models\Company;
use yii\helpers\ArrayHelper;

/**
 * This is the class for controller "CompanyController".
 */
class CompanyController extends \backend\controllers\base\CompanyController
{
    public function actionUpdate($id)
    {
        $string = 'AllowedBusinessDirection';
        $model = $this->findModel($id);
        $result = $model->load($_POST) && Yii::$app->transaction->exec(function () use ($model, $string) {
                $imageAttribute = $model->getImageAttribute();
                if (ArrayHelper::getValue($_POST, 'delete_logo')) {
                    $model->$imageAttribute = null;
                } else {
                    $model->setScenario(Company::SCENARIO_INSERT);
                    $model->$imageAttribute = UploadedFile::getInstance($model, $imageAttribute);
                }
                if (!$model->save()) {
                    return false;
                }

                $allowedBusinessDirections = (isset($_POST[$string]) && is_array($_POST[$string])) ? array_keys($_POST[$string]) : [];
                $rows = $model->getCompanyBusinessDirections()->all();
                foreach ($rows as $row) {
                    if (!$row->delete()) {
                        return false;
                    }
                }

                foreach ($allowedBusinessDirections as $allowedBusinessDirection) {
                    $cbd = new CompanyBusinessDirection();
                    $cbd->company_id = $model->id;
                    $cbd->business_direction_id = intval($allowedBusinessDirection);
                    if (!$cbd->save()) {
                        return false;
                    }
                }

                return true;
            });
        return $result ? $this->redirect(['update', 'id' => $model->id]) : $this->render('update', ['model' => $model]);
    }

    public function actionLogoRequirements()
    {
        return $this->renderAjax('logo-requirements');
    }
}
