<?php
/**
 * /var/www/html/frontend/runtime/giiant/49eb2de82346bc30092f584268252ed2
 *
 * @package default
 */

namespace frontend\controllers;

use common\controllers\BaseController;
use common\enums\Permission;
use common\models\Driver;
use common\models\DriverCompliance;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

/**
 * This is the class for controller "DriverComplianceController".
 */
class DriverComplianceController extends BaseController
{
    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'view', 'create', 'update', 'delete'],
                'permissions' => [Permission::ADD_EDIT_DRIVERS]
            ]
        ];
    }
    public function actionUpdate($id)
    {
        $driver = $this->findModel($id);
        $model = $driver->driverCompliance;
        if (is_null($model)) {
            $model = new DriverCompliance();
            $model->driver_id = $driver->id;
        }

        if ($model->load($_POST)) {
            $model->driver_id = $driver->id;
            if ($model->save()) {
                return $this->redirect(Url::previous());
            }
        }

        return $this->render('update', [
            'model' => $model
        ]);
    }

    public function actionIndex()
    {
        $query = Driver::find()->joinWith(['office', 'driverCompliance' => function ($q) {
            $q->addSelect([
                '*',
                '(cdl_expires - CURRENT_DATE) AS cdl_expires_diff',
                '(haz_mat_expires - CURRENT_DATE) AS haz_mat_expires_diff',
                '(twic_exp - CURRENT_DATE) AS twic_exp_diff',
                '(last_drug_test - CURRENT_DATE) AS last_drug_test_diff',
                '(last_alcohol_test - CURRENT_DATE) AS last_alcohol_test_diff',
                '(work_auth_expires - CURRENT_DATE) AS work_auth_expires_diff',
                '(next_ffd_evaluation - CURRENT_DATE) AS next_ffd_evaluation_diff',
                '(next_h2s_certification - CURRENT_DATE) AS next_h2s_certification_diff',
                '(next_vio_review - CURRENT_DATE) AS next_vio_review_diff',
                '(next_mvr_review - CURRENT_DATE) AS next_mvr_review_diff',
                '(next_dot_physical - CURRENT_DATE) AS next_dot_physical_diff',
            ]);
        }]);
        Url::remember();
        return $this->render('index', ['dataProvider' => new ActiveDataProvider(['query' => $query, 'pagination' => false])]);
    }

    protected function findModel($id)
    {
        /** @var Driver $model */
        $model = Driver::find()->alias('t')->joinWith('driverCompliance')->andWhere(['t.id' => $id])->one();
        if (!$model)
            throw new NotFoundHttpException();

        return $model;
    }
}
