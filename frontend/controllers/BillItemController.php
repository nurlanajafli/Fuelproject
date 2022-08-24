<?php

namespace frontend\controllers;

use common\actions\FormProcessingAction;
use common\enums\Permission;
use common\models\Bill;
use common\models\BillItem;
use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;

class BillItemController extends base\BaseController
{
    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index', 'data', 'update'],
                'permissions' => [Permission::VIEW_BILLS]
            ],
            [
                'allow' => true,
                'actions' => ['index','data','update','delete','create','data'],
                'permissions' => [Permission::ADD_EDIT_BILLS]
            ],
        ];
    }

    public function actions()
    {
        return [
            'create' => [
                'class' => FormProcessingAction::class,
                'before' => function ($actionParams) {
                    return Bill::findOne($actionParams['id']);
                },
                'form' => function (Bill $bill) {
                    $result = new BillItem();
                    return $result;
                },
            ],
            'update' => [
                'class' => FormProcessingAction::class,
                'before' => function ($actionParams) {
                    return BillItem::findOne($actionParams['id']);
                },
                'form' => 'proxy',
            ],
        ];
    }

    public function actionData($billId, $mode)
    {
        $columns = [];
        if ($mode == 0) {
            // modal window
            $columns = [
                'account',
                'driver_id|rel=driver.getFullName()',
                'amount|decimal',
                'driver_id|rel=driver.office.office',
                new DataColumn([
                    'value' => function (BillItem $billItem) {
                        return '?';
                    }
                ]),
                'driver_id|rel=driver.user_defined_1',
                'special',
                'memo'
            ];
        }
        $grid = new Grid([
            'dataProvider' => new ActiveDataProvider([
                'query' => BillItem::find()->where(['bill_id' => $billId]),
                'pagination' => false,
            ]),
            'columns' => $columns
        ]);
        return $this->replyJson($grid->getData());
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->replyJson(['message' => 'Deleted']);
    }

    protected function findModel($billId)
    {
        /** @var Bill $bill */
        $bill = Bill::findOne($billId);
        if (!$bill) {
            throw new NotFoundHttpException();
        }

        return $bill;
    }
}
