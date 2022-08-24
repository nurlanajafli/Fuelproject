<?php

namespace frontend\controllers;

use common\actions\FormProcessingAction;
use common\enums\LoadStatus;
use common\enums\PayrollBatchType;
use common\models\Load;
use common\models\Payroll;
use common\models\PayrollAdjustment;
use common\models\PayrollPay;
use common\widgets\DataTables\CheckboxColumn;
use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use frontend\forms\payCalculator\ImportDispatchPay;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class PayCalculatorController extends base\BaseController
{
    public function allowedAttributes()
    {
        $model = new Payroll();
        return [
            'view' => [
                $model->formName() => [
                    'salary_amount', 'total_hours', 'ot_hours', 'ot_2_hours', 'st_rate', 'ot_rate', 'ot_2_rate', 'description', 'other_pay_amount'
                ]
            ]
        ];
    }

    public function actions()
    {
        return [
            'view' => [
                'class' => FormProcessingAction::class,
                'before' => function ($actionParams) {
                    return $this->findModel($actionParams['id']);
                },
                'form' => 'proxy',
                'init' => function (Payroll $form, Payroll $model) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    $action->viewParams = [
                        'gridConfig' => [
                            'id' => 'padj-dt',
                            'ajax' => Url::toRoute(['payroll-adjustment/index', 'id' => $model->id]),
                            'columns' => [
                                'id|visible=false',
                                'type|title=Type',
                                'load_no|title=Load No',
                                'description|title=Description',
                                'percent|title=Percent|className=text-right',
                                'amount|title=Amount|className=text-right',
                                'charge|title=Charge',
                            ],
                            'template' => Yii::$app->params['dt']['templates'][0],
                            'order' => [],
                            'ordering' => false,
                            'attributes' => ['style' => 'margin:20px 0 0 0!important'],
                            'paging' => false,
                            'info' => false,
                            'foot' => false,
                            'searching' => false,
                            'colVis' => false,
                            'colReorder' => null,
                        ]
                    ];

                    if ($model->posted) {
                        $journalPostings = [
                            'allModels' => [],
                            'columns' => []
                        ];

                        if ($model->payrollBatch->type == PayrollBatchType::D_DRIVER) {
                            $journalPostings['columns'] = [
                                'date|title=Date|className=text-center',
                                'trans|title=Trans',
                                'GLAcct|title=GL Acct',
                                'description|title=Description',
                                'credit|title=Credit|className=text-right|decimal',
                                'debit|title=Debit|className=text-right|decimal',
                                'name|title=Name',
                                'batch|title=Batch|className=text-center',
                                'memo|title=Memo'
                            ];

                            $date = Yii::$app->formatter->asDate($model->payrollBatch->check_date, Yii::$app->params['formats'][1]);
                            $netAmount = $model->netamount;
                            $fullName = $model->driver->getFullName();

                            $journalPostings['allModels'][] = [
                                'date' => $date,
                                'trans' => Yii::t('app', 'PayChk'),
                                'GLAcct' => $model->bank_account,
                                'description' => $model->bankAccount ? $model->bankAccount->description : '',
                                'credit' => $netAmount,
                                'debit' => '',
                                'name' => $fullName,
                                'batch' => $model->payroll_batch_id,
                                'memo' => '',
                            ];
                            $journalPostings['allModels'][] = [
                                'date' => $date,
                                'trans' => Yii::t('app', 'Payroll'),
                                'GLAcct' => $model->d_expense_acct,
                                'description' => $model->dExpenseAcct ? $model->dExpenseAcct->description : '',
                                'credit' => '',
                                'debit' => $netAmount,
                                'name' => $fullName,
                                'batch' => $model->payroll_batch_id,
                                'memo' => '',
                            ];

                            /** @var PayrollAdjustment[] $rows */
                            $rows = $model->getPayrollAdjustments()->alias('t')->joinWith('dAccount')->orderBy('t.created_at, t.id')->all();
                            foreach ($rows as $row) {
                                $journalPostings['allModels'][] = [
                                    'date' => $date,
                                    'trans' => Yii::t('app', 'Payroll'),
                                    'GLAcct' => $row->d_account,
                                    'description' => $row->dAccount ? $row->dAccount->description : '',
                                    'credit' => $row->d_amount,
                                    'debit' => '',
                                    'name' => $fullName,
                                    'batch' => $model->payroll_batch_id,
                                    'memo' => $row->d_payroll_adjustment_code,
                                ];
                                $journalPostings['allModels'][] = [
                                    'date' => $date,
                                    'trans' => Yii::t('app', 'Payroll'),
                                    'GLAcct' => $model->d_expense_acct,
                                    'description' => $model->dExpenseAcct ? $model->dExpenseAcct->description : '',
                                    'credit' => '',
                                    'debit' => $row->d_amount,
                                    'name' => $fullName,
                                    'batch' => $model->payroll_batch_id,
                                    'memo' => $row->d_payroll_adjustment_code,
                                ];
                            }
                        }
                        $action->viewParams['journalPostings'] = $journalPostings;
                    } else {
                        $action->viewParams['gridConfig']['doubleClick'] = ['modal', Url::toRoute(['payroll-adjustment/update', 'id' => 'col:0'])];
                    }
                },
                'save' => function (Payroll $form, Payroll $model, string $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;

                    if (!$form->posted) {
                        $this->saveModel($form);
                    }

                    return $action->saveResp(true, ['payroll-batch/index']);
                },
                'validateResp' => function (Payroll $form, $errors) {
                    $array = $form->calcOutcomeFields();
                    $array['outcome'] = $this->renderPartial('_outcome', ['payroll' => $form]);
                    return $array;
                }
            ],
            'import-dispatch-pay' => [
                'class' => FormProcessingAction::class,
                'before' => function ($actionParams) {
                    return $this->findModel($actionParams['id']);
                },
                'form' => function (Payroll $model) {
                    $form = new ImportDispatchPay();
                    $payrollPayInfo = $this->getPayrollPayInfo($model);
                    $form->ids = ArrayHelper::map($model->payrollPays, $payrollPayInfo['attribute'], $payrollPayInfo['attribute']);
                    return $form;
                },
                'init' => function (ImportDispatchPay $form, Payroll $model) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    $payrollPayInfo = $this->getPayrollPayInfo($model);
                   // $payrollPayInfoCo = $this->getPayrollPayInfoCo($model);
                    $action->viewParams = [
                        'gridConfig' => [
                            'dataProvider' => new ActiveDataProvider([
                                'query' => $payrollPayInfo['query'],
                                'pagination' => false
                            ]),
                            'columns' => $payrollPayInfo['columns'],
                            'template' => Yii::$app->params['dt']['templates'][0],
                            'colReorder' => null,
                            'order' => [],
                            'ordering' => false,
                            'attributes' => ['style' => 'margin:0!important;'],
                            'paging' => false,
                            'info' => false,
                            'foot' => false,
                            'searching' => false,
                            'colVis' => false,
                            'id' => 'dt-payroll-pay',
                            'selectedRows' => $form->ids,
                            'orderCellsTop' => true,
                            'autoWidth' => false,
                            'initComplete' => 'payrollPayCalcSum($("#" + settings.sInstance).DataTable(), true);'
                        ],
                        'saveBtn' => !$model->posted
                    ];
                    if (!$model->posted) {
                        $action->viewParams['gridConfig']['select'] = ['style' => 'multi', 'items' => 'row'];
                        $action->viewParams['gridConfig']['buttons'] = [Grid::BUTTON_SELECT, Grid::BUTTON_DESELECT];
                    }
                },
                'save' => function (ImportDispatchPay $form, Payroll $model, string $button) {
                    /** @var FormProcessingAction $action */
                    $action = $this->action;
                    $f = true;
                    if (!$model->posted) {
                        $f = Yii::$app->transaction->exec(function () use ($form, $model) {
                            $payrollPayInfo = $this->getPayrollPayInfo($model);
                            $attribute = $payrollPayInfo['attribute'];
                            $ids1 = ArrayHelper::map($payrollPayInfo['query']->andWhere(['t.id' => $form->ids])->all(), 'id', 'id');
                            $ids2 = ArrayHelper::map($model->payrollPays, $attribute, $attribute);
                            /** @var PayrollPay[] $rows */
                            $rows = $model->getPayrollPays()->andWhere([$attribute => array_diff($ids2, $ids1)])->all();
                            foreach ($rows as $row) {
                                PayrollPay::deleteAll(['id' => $row->id]);
                            }
                            $array = array_diff($ids1, $ids2);
                            foreach ($array as $id) {
                                $payrollPay = new PayrollPay();
                                $payrollPay->payroll_id = $model->id;
                                $payrollPay->setScenario($model->payrollBatch->type);
                                $payrollPay->$attribute = $id;
                                $this->saveModel($payrollPay);
                            }
                            $model->calcPays();
                            return $this->saveModel($model);
                        });
                    }
                    return $action->saveResp($f, ['outcome' => $this->renderPartial('_outcome', ['payroll' => $model])]);
                }
            ]
        ];
    }

    public function actionDelete($id)
    {
        $payroll = $this->findModel($id);
        if (!$payroll->posted) {
            Yii::$app->transaction->exec(function () use ($payroll) {
                PayrollAdjustment::deleteAll(['payroll_id' => $payroll->id]);
                PayrollPay::deleteAll(['payroll_id' => $payroll->id]);
                Payroll::deleteAll(['id' => $payroll->id]);
                return true;
            });
        }
        return $this->redirect(['payroll-batch/index']);
    }

    protected function findModel($id): Payroll
    {
        if (is_null($model = Payroll::find()->
        alias('t')->
        joinWith(['payrollBatch', 'payrollPays', 'driver', 'bankAccount', 'dExpenseAcct'])->
        where(['t.id' => $id])->
        one()))
            throw new NotFoundHttpException();

        return $model;
    }

    protected function getPayrollPayInfo(Payroll $payroll)
    {
        switch ($payroll->payrollBatch->type) {
            case PayrollBatchType::D_DRIVER:
                return [
                    'query' => Load::find()->
                    alias('t')->
                    joinWith(['loadStops.state', 'dispatchAssignment da'])->
                    andWhere(['t.status' => LoadStatus::COMPLETED])->
                    andWhere(['>=', 't.arrived_date', $payroll->payrollBatch->period_start])->
                    andWhere(['<=', 't.arrived_date', $payroll->payrollBatch->period_end])->
                    andWhere(new Expression('da.driver_id=:driver_id', [':driver_id' => $payroll->driver_id]))->
                    orWhere(new Expression('da.codriver_id=:codriver_id', [':codriver_id' => $payroll->driver_id]))->
                    orderBy('t.arrived_date'),
                    'columns' => [
                        'id|visible=false',
                        'arrived_date|className=text-center|title=Date|date=' . Yii::$app->params['formats'][2],
                        'id|title=Load|className=text-center',
                        new DataColumn([
                            'title' => Yii::t('app', 'From'),
                            'value' => function (Load $load) {
                                if ($load->loadStops) {
                                    return $load->loadStops[0]->state ?
                                        sprintf('%s, %s', $load->loadStops[0]->city, $load->loadStops[0]->state->state_code) :
                                        $load->loadStops[0]->city;
                                }
                                return '';
                            }
                        ]),
                        new DataColumn([
                            'title' => Yii::t('app', 'To'),
                            'value' => function (Load $load) {
                                if ($load->loadStops) {
                                    $i = count($load->loadStops) - 1;
                                    return $load->loadStops[$i]->state ?
                                        sprintf('%s, %s', $load->loadStops[$i]->city, $load->loadStops[$i]->state->state_code) :
                                        $load->loadStops[$i]->city;
                                }
                                return '';
                            }
                        ]),
                        'id|rel=dispatchAssignment.pay_code|title=Pay Code',
                        'id|rel=dispatchAssignment.driver_pay_type|title=Type',
                        'bill_miles|integer|title=LM|className=text-right',
                        new DataColumn([
                            'title' => Yii::t('app', 'EM'),
                            'value' => $emptyMiles = function (Load $load) use ($payroll) {
                                return $load->dispatchAssignment->driver_empty_miles;
                            },
                            'tags' => 'integer|className=text-right'
                        ]),
                        new DataColumn([
                            'title' => Yii::t('app', 'Total amount'),
                            'value' => $pay = function (Load $load) use ($payroll) {
                                return $load->dispatchAssignment->driver_total_pay;
                            },
                            'tags' => 'decimal|className=text-right text-success'
                        ]),
                        new DataColumn([
                            'title' => Yii::t('app', 'Percent'),
                            'value' => $percent = function (Load $load) use ($payroll) {
                                if($load->dispatchAssignment->codriver_id == '' || $load->dispatchAssignment->codriver_id == 0
                                    || is_null($load->dispatchAssignment->codriver_id )) {
                                    return 1;
                                } else {
                                    if($payroll->driver_id == $load->dispatchAssignment->codriver_id) {
                                        return $load->dispatchAssignment->driver->co_driver_earning_percent;
                                    } else {
                                        return 1-$load->dispatchAssignment->driver->co_driver_earning_percent;
                                    }
                                }
                            },
                            'tags' => 'decimal|className=text-right'
                        ]),
                        new DataColumn([
                            'title' => Yii::t('app', 'Pay amount'),
                            'value' => $percent = function (Load $load) use ($payroll) {
                                if($load->dispatchAssignment->codriver_id == '' || $load->dispatchAssignment->codriver_id == 0
                                    || is_null($load->dispatchAssignment->codriver_id )) {
                                    return $load->dispatchAssignment->driver_total_pay;
                                } else {
                                    if($payroll->driver_id == $load->dispatchAssignment->codriver_id) {
                                        return $load->dispatchAssignment->driver_total_pay*$load->dispatchAssignment->driver->co_driver_earning_percent;
                                    } else {
                                        return $load->dispatchAssignment->driver_total_pay*(1-$load->dispatchAssignment->driver->co_driver_earning_percent);
                                    }
                                }
                            },
                            'tags' => 'decimal|className=text-right text-success'
                        ]),
                        'freight|title=Frt Rev|decimal|className=text-right',
                        'total|title=Tot Rev|decimal|className=text-right',
                        new DataColumn([
                            'title' => Yii::t('app', 'Inv'),
                            'value' => function (Load $load) {
                                return '';
                            }
                        ]),
                        new CheckboxColumn([
                            'title' => Yii::t('app', 'BU'),
                            'attribute' => 'backupcleared'
                        ]),
                        new CheckboxColumn([
                            'title' => Yii::t('app', 'Clr'),
                            'attribute' => 'loadcleared'
                        ]),
                        new DataColumn([
                            'title' => Yii::t('app', 'Var'),
                            'value' => function (Load $load) {
                                return '';
                            }
                        ]),
                        new DataColumn([
                            'title' => Yii::t('app', 'Fin'),
                            'value' => function (Load $load) {
                                return 'Y';
                            },
                            'tags' => 'className=text-center text-primary'
                        ]),
                        'bill_miles|visible=false',
                        new DataColumn([
                            'visible' => false,
                            'value' => $emptyMiles
                        ]),
                        new DataColumn([
                            'visible' => false,
                            'value' => $pay
                        ]),
                    ],
                    'attribute' => 'd_load_id'
                ];
        }
    }
}
