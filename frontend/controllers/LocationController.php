<?php
/**
 * /var/www/html/frontend/runtime/giiant/49eb2de82346bc30092f584268252ed2
 *
 * @package default
 */


namespace frontend\controllers;

use common\enums\Permission;
use common\models\LocationContact;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * This is the class for controller "LocationController".
 */
class LocationController extends base\LocationController
{
    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index','view'],
                'permissions' => [Permission::LOCATION_LISTING]
            ],
            [
                'allow' => true,
                'actions' => ['index','view','create','update','delete','contact-form','contact-delete'],
                'permissions' => [Permission::ADD_EDIT_LOCATIONS]
            ],
        ];
    }

    public function actionContactForm($location, $id = null)
    {
        if ($id) {
            $model = LocationContact::findOne($id);
        } else {
            $model = new LocationContact();
            $model->location_id = $location;
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($this->request->isPost) {
            if ($model->load($_POST) && $model->save()) {
                return $this->redirect(['update', 'id' => $model->location_id, 'tab' => 'contacts']);
            }
        }

        return $this->renderAjax('contactForm', [
            'model' => $model
        ]);
    }

    public function actionContactDelete($location, $id)
    {
        LocationContact::deleteAll(['id' => $id, 'location_id' => $location]);
        return $this->redirect(['update', 'id' => $location, 'tab' => 'contacts']);
    }
}
