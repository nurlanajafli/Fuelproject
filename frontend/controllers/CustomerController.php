<?php
/**
 * /var/www/html/frontend/runtime/giiant/49eb2de82346bc30092f584268252ed2
 *
 * @package default
 */

namespace frontend\controllers;

use common\actions\FormProcessingAction;
use common\enums\Permission;
use common\models\Customer;
use common\models\CustomerContact;
use common\models\CustomerContactNote;
use common\widgets\DataTables\Grid;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * This is the class for controller "CustomerController".
 */
class CustomerController extends base\CustomerController
{

    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index','view'],
                'permissions' => [Permission::CUSTOMER_LISTING]
            ],
            [
                'allow' => true,
                'actions' => ['index','view','create','update','delete','create-contact-note','contact-notes','contact-form', 'contact-delete'],
                'permissions' => [Permission::ADD_EDIT_CUSTOMERS]
            ],
        ];
    }

    public function actions()
    {
        return [
            'create-contact-note' => [
                'class' => FormProcessingAction::class,
                'formClass' => \frontend\forms\CustomerContactNote::class,
                'before' => function ($actionParams) {
                    return Customer::findOne($actionParams['id']);
                },
                'viewParams' => function ($model) {
                    /** @var Customer $model */
                    return [
                        'customer' => $model
                    ];
                },
                'save' => function (\frontend\forms\CustomerContactNote $form, Customer $model, $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;

                    $newModel = $form->getModel();
                    $newModel->customer_id = $model->id;
                    $action->saveResp($this->saveModel($newModel));
                    return $this->redirect(['index']);
                }
            ]
        ];
    }

    public function actionContactNotes($id)
    {
        $grid = new Grid([
            'dataProvider' => new ActiveDataProvider([
                'query' => CustomerContactNote::find()->where(['customer_id' => $id]),
                'pagination' => false
            ]),
            'columns' => [
                'next_contact|datetime=' . Yii::$app->params['formats'][4],
                'code',
                'notes'
            ]
        ]);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $grid->getData();
    }

    public function actionContactForm($customer, $id = null)
    {
        if ($id) {
            $model = CustomerContact::findOne($id);
        } else {
            $model = new CustomerContact();
            $model->customer_id = $customer;
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($this->request->isPost) {
            if ($model->load($_POST) && $model->save()) {
                return $this->redirect(['update', 'id' => $model->customer_id, 'tab' => 'contacts']);
            }
        }

        return $this->renderAjax('contactForm', [
            'model' => $model
        ]);
    }

    public function actionContactDelete($id, $customer)
    {
        CustomerContact::deleteAll(['id' => $id]);
        return $this->redirect(['update', 'id' => $customer, 'tab' => 'contacts']);
    }
}
