<?php
/**
 * /var/www/html/frontend/runtime/giiant/49eb2de82346bc30092f584268252ed2
 *
 * @package default
 */

namespace frontend\controllers;

use common\enums\Permission;
use common\models\Carrier;
use common\models\CarrierContact;
use common\models\CarrierProfile;
use common\models\Lane;
use common\models\LanePreference;
use Exception;
use Yii;
use yii\helpers\Url;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * This is the class for controller "CarrierController".
 */
class CarrierController extends base\CarrierController
{
    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index','view'],
                'permissions' => [Permission::CARRIER_LISTING]
            ],
            [
                'allow' => true,
                'actions' => ['index','view','create','update','delete','contact-form','contact-delete'],
                'permissions' => [Permission::ADD_EDIT_CARRIERS]
            ],
        ];
    }

    private function save($id)
    {
        $model = $id ? $this->findModel($id) : new Carrier();
        $profileModel = $model->carrierProfile ? $model->carrierProfile : new CarrierProfile();
        $lanePreferenceModel = $model->lanePreference ? $model->lanePreference : new LanePreference();
        $laneModels = $lanePreferenceModel->getLanes()->all();
        if (Yii::$app->request->isPost) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->load($_POST) && $model->save()) {
                }
            } catch (Exception $e) {
                $model->addError('_exception', isset($e->errorInfo[2]) ? $e->errorInfo[2] : $e->getMessage());
            }
            try {
                $profileModel->carrier_id = $model->id;
                if ($profileModel->load($_POST) && $profileModel->save()) {
                } else {
                    $profileModel->clearErrors('carrier_id');
                }
            } catch (Exception $e) {
                $profileModel->addError('_exception', isset($e->errorInfo[2]) ? $e->errorInfo[2] : $e->getMessage());
            }
            try {
                $lanePreferenceModel->carrier_id = $model->id;
                if ($lanePreferenceModel->load($_POST) && $lanePreferenceModel->save()) {
                } else {
                    $lanePreferenceModel->clearErrors('carrier_id');
                }
            } catch (Exception $e) {
                $lanePreferenceModel->addError('_exception', isset($e->errorInfo[2]) ? $e->errorInfo[2] : $e->getMessage());
            }
            $j = count($laneModels);
            $laneHasErrors = false;
            for ($i = 1; $i <= Yii::$app->params['lane']['max']; $i++) {
                $m = ($i <= $j) ? $laneModels[$i - 1] : new Lane();
                try {
                    $m->preference_id = $lanePreferenceModel->id;
                    if ($m->load($_POST['Lane'], $i - 1) && $m->save()) {
                    } else {
                        $laneHasErrors = true;
                        $m->clearErrors('preference_id');
                    }
                } catch (Exception $e) {
                    $m->addError('_exception', isset($e->errorInfo[2]) ? $e->errorInfo[2] : $e->getMessage());
                }
                if ($i > $j) {
                    array_push($laneModels, $m);
                }
            }
            if ($model->hasErrors() || $profileModel->hasErrors() || $lanePreferenceModel->hasErrors() || $laneHasErrors) {
                $transaction->rollBack();
            } else {
                $transaction->commit();
                return $this->redirect($id ? Url::previous() : ['index']);
            }
        }
        return $this->render($id ? 'update' : 'create', [
            'model' => $model,
            'profileModel' => $profileModel,
            'lanePreferenceModel' => $lanePreferenceModel,
            'laneModels' => $laneModels,
        ]);
    }

    public function actionCreate()
    {
        return $this->save(null);
    }

    public function actionUpdate($id)
    {
        return $this->save($id);
    }

    public function actionContactForm($carrier, $id = null)
    {
        if ($id) {
            $model = CarrierContact::findOne($id);
        } else {
            $model = new CarrierContact();
            $model->carrier_id = $carrier;
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($this->request->isPost) {
            if ($model->load($_POST) && $model->save()) {
                return $this->redirect(['update', 'id' => $model->carrier_id, 'tab' => 'contacts']);
            }
        }

        return $this->renderAjax('contactForm', [
            'model' => $model
        ]);
    }

    public function actionContactDelete($id, $carrier)
    {
        CarrierContact::deleteAll(['id' => $id]);
        return $this->redirect(['update', 'id' => $carrier, 'tab' => 'contacts']);
    }
}
