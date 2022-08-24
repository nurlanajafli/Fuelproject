<?php
/**
 * /var/www/html/frontend/runtime/giiant/49eb2de82346bc30092f584268252ed2
 *
 * @package default
 */


namespace frontend\controllers;

use common\enums\Permission;
use common\models\VendorContact;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * This is the class for controller "VendorController".
 */
class VendorController extends base\VendorController
{

    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index','view'],
                'permissions' => [Permission::VENDOR_LISTING]
            ],
            [
                'allow' => true,
                'actions' => ['index','view','create','update','delete','contact-form','contact-delete'],
                'permissions' => [Permission::ADD_EDIT_VENDORS]
            ],
        ];
    }

    public function actionContactForm($vendor, $id = null)
    {
        if ($id) {
            $model = VendorContact::findOne($id);
        } else {
            $model = new VendorContact();
            $model->vendor_id = $vendor;
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($this->request->isPost) {
            if ($model->load($_POST) && $model->save()) {
                return $this->redirect(['update', 'id' => $model->vendor_id, 'tab' => 'contacts']);
            }
        }

        return $this->renderAjax('contactForm', [
            'model' => $model
        ]);
    }

    public function actionContactDelete($id, $vendor)
    {
        VendorContact::deleteAll(['id' => $id]);
        return $this->redirect(['update', 'id' => $vendor, 'tab' => 'contacts']);
    }
}
