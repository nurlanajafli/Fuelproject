<?php

namespace frontend\controllers;

use common\actions\FormProcessingAction;
use common\enums\AdjustmentCalcBy;
use common\models\Driver;
use common\models\DriverAdjustment;
use common\widgets\DataTables\CheckboxColumn;
use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use frontend\actions\driverAdjustment\UpdateAction;
use yii\data\ActiveDataProvider;

class DriverAdjustmentController extends base\BaseController
{
    public function allowedAttributes()
    {
        $model = new DriverAdjustment();
        return [
            'create' => $array = [
                $model->formName() => [
                    'payroll_adjustment_code', 'post_to_carrier_id', 'post_to_driver_id', 'post_to_vendor_id', 'account', 'calc_by', 'amount', 'cap_id', 'truck_id'
                ]
            ],
            'update' => $array
        ];
    }

    public function actions()
    {
        return [
            'create' => [
                'class' => FormProcessingAction::class,
                'view' => 'createUpdate',
                'before' => function ($actionParams) {
                    return Driver::findOne($actionParams['id']);
                },
                'form' => function (Driver $driver) {
                    $model = new DriverAdjustment();
                    $model->driver_id = $driver->id;
                    return $model;
                },
                'save' => function (DriverAdjustment $form, Driver $model, string $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    return $action->saveResp($this->saveModel($form));
                }
            ],
            'update' => [
                'class' => UpdateAction::class,
                'view' => 'createUpdate',
                'before' => function ($actionParams) {
                    return DriverAdjustment::findOne(['id' => $actionParams['id'], 'driver_id' => $actionParams['driverId']]);
                },
                'form' => function (DriverAdjustment $model) {
                    return $model;
                },
                'save' => function (DriverAdjustment $form, DriverAdjustment $model, string $button) {
                    /** @var UpdateAction $action */
                    $action = $this->action;

                    if ($button == 'delete') {
                        $form->delete();
                        return $action->saveResp(true);
                    }

                    return $action->saveResp($this->saveModel($form));
                }
            ]
        ];
    }

    public function actionIndex($id)
    {
        return $this->replyJson((new Grid([
            'dataProvider' => new ActiveDataProvider([
                'query' => DriverAdjustment::find()->
                alias('t')->
                joinWith(['payrollAdjustmentCode', 'postToCarrier', 'postToDriver', 'postToVendor'])->
                where(['t.driver_id' => $id])->
                orderBy('t.created_at'),
                'pagination' => false
            ]),
            'columns' => [
                'id',
                'driver_id',
                'payroll_adjustment_code',
                new DataColumn([
                    'value' => function (DriverAdjustment $model) {
                        if ($model->postToCarrier) {
                            return $model->postToCarrier->name;
                        }

                        if ($model->postToDriver) {
                            return $model->postToDriver->getFullName();
                        }

                        if ($model->postToVendor) {
                            return $model->postToVendor->name;
                        }

                        return '';
                    }
                ]),
                'percent|decimal',
                new DataColumn([
                    'value' => function (DriverAdjustment $model) {
                        return ($model->calc_by == AdjustmentCalcBy::FLAT_AMOUNT
                            ? $model->amount
                            : 0
                        );
                    }
                ]),
                new DataColumn([
                    'value' => function (DriverAdjustment $model) {
                        return 0;
                    }
                ]),
                new CheckboxColumn([
                    'value' => function (DriverAdjustment $model) {
                        return false;
                    }
                ])
            ]
        ]))->getData());
    }
}