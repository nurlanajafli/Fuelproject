<?php

namespace frontend\controllers;

use common\actions\FormProcessingAction;
use common\enums\Permission;
use common\helpers\DateTime;
use common\models\FuelPurchase;
use common\widgets\DataTables\Grid;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\validators\DateValidator;

class FuelPurchaseController extends base\BaseController
{
    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index','update','create','data'],
                'permissions' => [Permission::ADD_EDIT_PURCHASE_ORDERS]
            ],
        ];
    }
    public function actions()
    {
        return [
            'create' => [
                'class' => FormProcessingAction::class,
                'before' => function ($actionParams) {
                    return true;
                },
                'form' => function ($bool) {
                    $result = new FuelPurchase();
                    $result->purchase_date = Yii::$app->formatter->asDate('now', Yii::$app->params['formats']['db']);
                    $result->quantity = 0;
                    $result->cost = 0;
                    $result->ppg = 0;
                    return $result;
                },
                'save' => function (FuelPurchase $form, $bool, $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    return $action->saveResp($this->saveModel($form), [], ['index']);
                }
            ],
            'update' => [
                'class' => FormProcessingAction::class,
                'before' => function ($actionParams) {
                    return FuelPurchase::findOne($actionParams['id']);
                },
                'form' => 'proxy',
                'save' => function (FuelPurchase $form, FuelPurchase $model, $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    if ($button == 'delete') {
                        $model->delete();
                        return $action->saveResp(true, [], ['index']);
                    }

                    return $action->saveResp($this->saveModel($form), [], ['index']);
                }
            ]
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionData($startDate = null, $endDate = null)
    {
        $expression = '1=1';
        $params = [];
        $validator = new DateValidator();
        $validator->format = Yii::$app->params['formats'][13];
        if ($validator->validate($startDate)) {
            $expression = 'purchase_date >= :startDate';
            $params[':startDate'] = DateTime::transformDate($startDate, $validator->format, Yii::$app->params['formats']['db']);
        }
        if ($validator->validate($endDate)) {
            $expression .= ' AND purchase_date <= :endDate';
            $params[':endDate'] = DateTime::transformDate($endDate, $validator->format, Yii::$app->params['formats']['db']);
        }
        $grid = new Grid([
            'dataProvider' => new ActiveDataProvider([
                'query' => FuelPurchase::find()->andWhere(new Expression($expression, $params)),
                'pagination' => false,
            ]),
            'columns' => [
                'trip_no',
                'purchase_date|date=' . $validator->format,
                'purchase_time|time=' . Yii::$app->params['formats']['24h'],
                'id',
                'driver_id|rel=driver.office.office',
                'driver_id|rel=driver.fullName',
                'truck_id|rel=truck.truck_no',
                'vendor',
                'city',
                'state_id|rel=state.state_code',
                'quantity|decimal',
                'cost|decimal',
                'ppg|decimal',
            ],
        ]);
        return $this->replyJson($grid->getData());
    }
}
