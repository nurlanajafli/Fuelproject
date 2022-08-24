<?php

namespace frontend\controllers;

use common\actions\FormProcessingAction;
use common\enums\PayrollBatchStatus;
use common\enums\PayrollBatchType;
use common\enums\Permission;
use common\models\Driver;
use common\models\Payroll;
use common\models\PayrollAdjustment;
use common\models\PayrollBatch;
use common\models\PayrollPay;
use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use frontend\forms\payrollBatch\Edit;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

class PayrollBatchController extends base\BaseController
{
    protected function accessRules()
    {
        return [
            [
                'allow' => true,
                'actions' => ['index','create','update','delete','finish','payrolls'],
                'permissions' => [Permission::PAYROLL_JOURNAL]
            ]
        ];
    }
    //index, create, update, delete, finish, payrolls,

    public function actions()
    {
        return [
            'create' => $array = [
                'class' => FormProcessingAction::class,
                'view' => 'edit',
                'before' => function ($actionParams) {
                    return ArrayHelper::isIn($actionParams['id'], [PayrollBatchType::D_DRIVER]/* PayrollBatchType::getEnums() */);
                },
                'form' => function ($b) {
                    /** @var PayrollBatch $lpb */
                    $lpb = PayrollBatch::find()->select(
                        new Expression("(period_end + integer '1') AS period_start, check_date AS period_end, (check_date + (period_end - period_start) + integer '1') AS check_date"))->orderBy(['created_at' => SORT_DESC])->one();
                    $payrollBatch = new PayrollBatch();
                    $payrollBatch->type = $this->actionParams['id'];
                    $payrollBatch->status = PayrollBatchStatus::UNFINISHED;
                    $editForm = new Edit();
                    $editForm->batchDate = Yii::$app->formatter->asDate('now', Yii::$app->params['formats']['db']);
                    if ($lpb) {
                        $editForm->checkDate = $lpb->check_date;
                        $editForm->periodStart = $lpb->period_start;
                        $editForm->periodEnd = $lpb->period_end;
                    } else {
                        $editForm->checkDate = Yii::$app->formatter->asDate(strtotime('+13 days'), Yii::$app->params['formats']['db']);
                        $editForm->periodStart = $editForm->batchDate;
                        $editForm->periodEnd = Yii::$app->formatter->asDate(strtotime('+6 days'), Yii::$app->params['formats']['db']);
                    }
                    $editForm->ids = [];
                    $editForm->setPayrollBatch($payrollBatch);
                    return $editForm;
                },
                'init' => function (Edit $form, $model) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    $payrollBatch = $form->getPayrollBatch();
                    $payrollInfo = $this->getPayrollForInfo($payrollBatch);
                    if (!$payrollBatch->isNewRecord) {
                        $form->ids = ArrayHelper::map($payrollBatch->payrolls, $payrollInfo['attribute'], $payrollInfo['attribute']);
                    }
                    $action->viewParams = [
                        'gridConfig' => [
                            'dataProvider' => new ActiveDataProvider([
                                'query' => $payrollInfo['query'],
                                'pagination' => false
                            ]),
                            'columns' => $payrollInfo['columns'],
                            'template' => Yii::$app->params['dt']['templates'][0],
                            'order' => [[1, 'asc']],
                            'colReorder' => null,
                            'colVis' => null,
                            'id' => 'dt-payroll-for',
                            'selectedRows' => $form->ids,
                            'paging' => false,
                            // 'scrollY' => '300px',
                            // 'scrollCollapse' => true,
                            'info' => false
                        ],
                        'saveBtn' => $saveBtn = $this->canSave($payrollBatch),
                        'deleteBtn' => $this->canDelete($payrollBatch),
                    ];
                    if ($saveBtn) {
                        $action->viewParams['gridConfig']['select'] = ['style' => 'multi', 'items' => 'row'];
                        $action->viewParams['gridConfig']['buttons'] = [Grid::BUTTON_SELECT, Grid::BUTTON_DESELECT];
                    }
                },
                'save' => function (Edit $form, $model, $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;

                    $payrollBatch = $form->getPayrollBatch();
                    if (!$this->canSave($payrollBatch)) {
                        return $this->redirect(['index']);
                    }
                    $payrollBatch->batch_date = $form->batchDate;
                    $payrollBatch->period_start = $form->periodStart;
                    $payrollBatch->period_end = $form->periodEnd;
                    $payrollBatch->check_date = $form->checkDate;
                    $f = Yii::$app->transaction->exec(function () use ($payrollBatch, $form) {
                        if (!$this->saveModel($payrollBatch)) {
                            return false;
                        }
                        $payrollInfo = $this->getPayrollForInfo($payrollBatch);
                        $rows = $payrollInfo['query']->andWhere(['t.id' => $form->ids])->all();
                        $models = [];
                        foreach ($rows as $row) {
                            $models[$row->id] = $row;
                        }
                        $ids1 = array_keys($models);
                        $ids2 = ArrayHelper::map($payrollBatch->payrolls, $payrollInfo['attribute'], $payrollInfo['attribute']);
                        /** @var Payroll[] $payrolls */
                        $payrolls = $payrollBatch->getPayrolls()->andWhere([$payrollInfo['attribute'] => array_diff($ids2, $ids1), 'posted' => false])->all();
                        foreach ($payrolls as $payroll) {
                            PayrollAdjustment::deleteAll(['payroll_id' => $payroll->id]);
                            PayrollPay::deleteAll(['payroll_id' => $payroll->id]);
                            Payroll::deleteAll(['id' => $payroll->id]);
                        }
                        $array = array_diff($ids1, $ids2);
                        foreach ($array as $id) {
                            $payroll = new Payroll();
                            $payroll->payroll_batch_id = $payrollBatch->id;
                            $payroll->setScenario($payrollBatch->type);
                            if ($payrollBatch->type == PayrollBatchType::D_DRIVER) {
                                /** @var Driver $driver */
                                $driver = $models[$id];
                                $payroll->driver_id = $id;
                                $payroll->driver_type = $driver->type;
                                $payroll->office_id = $driver->office_id;
                                $payroll->bank_account = $driver->bank_acct;
                                $payroll->d_expense_acct = $driver->expense_acct;
                                $payroll->pay_to_carrier_id = $driver->pay_to_carrier_id;
                                $payroll->pay_to_driver_id = $driver->pay_to_driver_id;
                                $payroll->pay_to_vendor_id = $driver->pay_to_vendor_id;
                                if (!$this->saveModel($payroll)) {
                                    continue;
                                }
                                foreach ($driver->driverAdjustments as $driverAdjustment) {
                                    $payrollAdjustment = new PayrollAdjustment();
                                    $payrollAdjustment->payroll_id = $payroll->id;
                                    $payrollAdjustment->d_payroll_adjustment_code = $driverAdjustment->payroll_adjustment_code;
                                    $payrollAdjustment->d_percent = $driverAdjustment->percent;
                                    $payrollAdjustment->d_amount = $driverAdjustment->amount;
                                    $payrollAdjustment->d_charge = 'EE';
                                    $payrollAdjustment->d_post_to_carrier_id = $driverAdjustment->post_to_carrier_id;
                                    $payrollAdjustment->d_post_to_driver_id = $driverAdjustment->post_to_driver_id;
                                    $payrollAdjustment->d_post_to_vendor_id = $driverAdjustment->post_to_vendor_id;
                                    $payrollAdjustment->d_account = $driverAdjustment->account;
                                    $payrollAdjustment->d_calc_by = $driverAdjustment->calc_by;
                                    $this->saveModel($payrollAdjustment);
                                }
                            } else {
                                $this->saveModel($payroll);
                            }
                        }
                        return true;
                    });
                    return $action->saveResp($f, ['index']);
                }
            ],
            'update' => ArrayHelper::merge($array, [
                'before' => function ($actionParams) {
                    return PayrollBatch::find()->alias('t')->joinWith('payrolls')->
                    where(['t.id' => $actionParams['id']])->one();
                },
                'form' => function (PayrollBatch $payrollBatch) {
                    $editForm = new Edit();
                    $editForm->batchDate = $payrollBatch->batch_date;
                    $editForm->checkDate = $payrollBatch->check_date;
                    $editForm->periodStart = $payrollBatch->period_start;
                    $editForm->periodEnd = $payrollBatch->period_end;
                    $editForm->ids = [];
                    $editForm->setPayrollBatch($payrollBatch);
                    return $editForm;
                },
            ])
        ];
    }

    public function actionDelete($id)
    {
        /** @var PayrollBatch $payrollBatch */
        $payrollBatch = PayrollBatch::find()->
        alias('t')->
        joinWith('payrolls')->
        where(['t.id' => $id])->
        one();
        if ($this->canDelete($payrollBatch)) {
            Yii::$app->transaction->exec(function () use ($payrollBatch) {
                foreach ($payrollBatch->payrolls as $payroll) {
                    PayrollAdjustment::deleteAll(['payroll_id' => $payroll->id]);
                    PayrollPay::deleteAll(['payroll_id' => $payroll->id]);
                }
                Payroll::deleteAll(['payroll_batch_id' => $payrollBatch->id]);
                PayrollBatch::deleteAll(['id' => $payrollBatch->id]);
                return true;
            });
        }
        return $this->redirect(['index']);
    }

    public function actionFinish($id)
    {
        /** @var PayrollBatch $payrollBatch */
        $payrollBatch = PayrollBatch::findOne(['id' => $id, 'status' => PayrollBatchStatus::UNFINISHED]);
        if ($payrollBatch) {
            $payrollBatch->status = PayrollBatchStatus::FINISHED;
            $this->saveModel($payrollBatch);
        }
        return $this->redirect(['index']);
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => PayrollBatch::find(),
            'pagination' => false
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    public function actionPayrolls($batchId)
    {
        $grid = new Grid([
            'dataProvider' => new ActiveDataProvider([
                'query' => Payroll::find()->where(['payroll_batch_id' => $batchId])->joinWith(['driver', 'office', 'payrollBatch']),
                'pagination' => false
            ]),
            'columns' => [
                'id',
                'null|method=getPayrollFor',
                'cd',
                'null|method=getCOS|abbreviation',
                'office_id|rel=office.office',
                'dispatch_pay|decimal',
                'mileage_pay|decimal',
                new DataColumn([
                    'value' => function (Payroll $payroll) {
                        return $payroll->deductions - $payroll->additions;
                    },
                    'tags' => 'decimal'
                ]),
                'netamount|decimal',
                'posted|yn=large',
                'null|rel=payrollBatch.period_start|date=' . Yii::$app->params['formats'][1],
                'null|rel=payrollBatch.period_end|date=' . Yii::$app->params['formats'][1],
                new DataColumn([
                    'value' => function (Payroll $payroll) {
                        return Yii::t('app', $payroll->posted ? 'Posted' : 'Unposted');
                    }
                ]),
            ]
        ]);
        return $this->replyJson($grid->getData());
    }

    protected function getPayrollForInfo(PayrollBatch $payrollBatch)
    {
        switch ($payrollBatch->type) {
            case PayrollBatchType::D_DRIVER:
                $dummyPayroll = new Payroll();
                return [
                    'query' => Driver::find()->alias('t')->
                    joinWith(['office', 'driverAdjustments' => function ($query) {
                        $query->orderBy('created_at');
                    }]),
                    'columns' => [
                        'id|visible=false',
                        'id|title=Name|method=getFullName|className=text-left',
                        'office_id|rel=office.office|className=text-left|title=' . $dummyPayroll->getAttributeLabel('office_id'),
                        'type|abbreviation=app|className=text-center|title=' . $dummyPayroll->getAttributeLabel('driver_type'),
                        new DataColumn([
                            'title' => Yii::t('app', 'Owner'),
                            'className' => 'text-center'
                        ]),
                        new DataColumn([
                            'title' => $dummyPayroll->getAttributeLabel('direct_deposit'),
                            'className' => 'text-center'
                        ]),
                        new DataColumn([
                            'title' => $dummyPayroll->getAttributeLabel('cd'),
                            'className' => 'text-center'
                        ])
                    ],
                    'attribute' => 'driver_id'
                ];
        }
    }

    protected function canSave(PayrollBatch $payrollBatch): bool
    {
        return $payrollBatch->status == PayrollBatchStatus::UNFINISHED;
    }

    protected function canDelete(PayrollBatch $payrollBatch): bool
    {
        return !$payrollBatch->isNewRecord && ($payrollBatch->status == PayrollBatchStatus::UNFINISHED) && !$payrollBatch->getPayrolls()->andWhere(['posted' => true])->exists();
    }
}
