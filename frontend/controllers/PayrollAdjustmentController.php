<?php

namespace frontend\controllers;

use common\actions\FormProcessingAction;
use common\enums\PayrollBatchType;
use common\models\Payroll;
use common\models\PayrollAdjustment;
use common\widgets\DataTables\Grid;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class PayrollAdjustmentController extends base\BaseController
{
    public function allowedAttributes()
    {
        $model = new PayrollAdjustment();
        return [
            'create' => $array = [
                $model->formName() => [
                    'd_payroll_adjustment_code',
                    'd_post_to_carrier_id',
                    'd_post_to_driver_id',
                    'd_post_to_vendor_id',
                    'd_account',
                    'd_calc_by',
                    'd_amount',
                    'd_load_id',
                    'd_description'
                ]
            ],
            'update' => $array
        ];
    }

    public function actions()
    {
        return [
            'create' => $array = [
                'class' => FormProcessingAction::class,
                'before' => function ($actionParams) {
                    return $this->findModel($actionParams['id']);
                },
                'form' => function (Payroll $payroll) {
                    $payrollAdjustment = new PayrollAdjustment();
                    $payrollAdjustment->payroll_id = $payroll->id;
                    if ($payroll->payrollBatch->type == PayrollBatchType::D_DRIVER) {
                        $payrollAdjustment->d_charge = 'EE';
                    }
                    $payrollAdjustment->setScenario($payroll->payrollBatch->type);
                    return $payrollAdjustment;
                },
                'init' => function (PayrollAdjustment $payrollAdjustment, Payroll $payroll) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    $action->viewParams = [
                        'type' => $payroll->payrollBatch->type
                    ];
                },
                'save' => function (PayrollAdjustment $payrollAdjustment, $model, string $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;

                    $payroll = ($model instanceof Payroll) ? $model : $payrollAdjustment->payroll;
                    $f = true;
                    if ($button == 'delete') {
                        if (!$payrollAdjustment->isNewRecord) {
                            PayrollAdjustment::deleteAll(['id' => $payrollAdjustment->id]);
                        }
                    } else {
                        $f = $this->saveModel($payrollAdjustment);
                    }

                    return $action->saveResp($f, ['outcome' => $this->renderPartial('//pay-calculator/_outcome', ['payroll' => $payroll])]);
                },
                'view' => 'edit'
            ],
            'update' => ArrayHelper::merge($array, [
                'before' => function ($actionParams) {
                    /** @var PayrollAdjustment $model */
                    $model = PayrollAdjustment::find()->alias('t')->
                    joinWith(['payroll p', 'payroll.payrollBatch'])->where(['t.id' => $actionParams['id'], 'p.posted' => false])->
                    one();
                    if ($model)
                        $model->setScenario($model->payroll->payrollBatch->type);
                    return $model;
                },
                'form' => 'proxy',
                'init' => function (PayrollAdjustment $form, PayrollAdjustment $model) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    $action->viewParams = [
                        'type' => $model->payroll->payrollBatch->type
                    ];
                },
            ])
        ];
    }

    public function actionIndex($id)
    {
        $model = $this->findModel($id, null);
        $config = ['dataProvider' => new ActiveDataProvider(['query' => $model->getPayrollAdjustments()->orderBy('created_at,id'), 'pagination' => false]), 'columns' => []];
        if ($model->payrollBatch->type == PayrollBatchType::D_DRIVER) {
            $config['columns'] = [
                'id',
                'd_payroll_adjustment_code',
                'd_load_id',
                'd_description',
                'd_percent|decimal',
                'd_amount|multiply=-1|decimal',
                'd_charge',
            ];
        }
        $grid = new Grid($config);
        return $this->replyJson($grid->getData());
    }

    protected function findModel($id, $posted = false): Payroll
    {
        $condition = ['t.id' => $id];
        if (!is_null($posted)) {
            $condition['t.posted'] = $posted;
        }
        if (is_null($model = Payroll::find()->alias('t')->joinWith(['payrollBatch', 'driver'])->andWhere($condition)->one()))
            throw new NotFoundHttpException();

        return $model;
    }
}
