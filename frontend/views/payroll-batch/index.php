<?php
/**
 * @var \yii\data\ActiveDataProvider $dataProvider
 * @var \common\components\View $this
 */

use common\enums\PayrollBatchStatus;
use common\enums\PayrollBatchType;
use common\models\Payroll;
use common\models\PayrollBatch;
use common\widgets\DataTables\DataColumn;
use common\widgets\DataTables\Grid;
use yii\helpers\Url;
use yii\helpers\Inflector;

$this->title = Yii::t('app', 'Payroll Batch Manager');
$this->params['breadcrumbs'][] = $this->title;
$dummyPayrollBatch = new PayrollBatch();
$dummyPayroll = new Payroll();
?>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?php echo $this->title ?></h1>
</div>
<div class="mb-4 card shadow" id="card-payroll-batch">
    <div class="card-header py-3">
        <div class="edit-form-toolbar">
            <button class="btn btn-link dropdowwn-toggle" data-tooltip="tooltip" data-toggle="dropdown"
                    data-placement="top">
                <i class="fas fa-play mr-1"></i><i class="fas fa-caret-down"></i>
            </button>
            <div class="dropdown-menu">
                <a href="#" class="dropdown-item js-ajax-modal"
                   data-url="<?php echo Url::toRoute(['create', 'id' => PayrollBatchType::D_DRIVER]) ?>"><?php echo Yii::t('app', 'Driver Payroll Batch') ?></a>
<!--                <a href="#" class="dropdown-item js-ajax-modal"-->
<!--                   data-url="--><?php //echo Url::toRoute(['pay-calculator/view', 'id' => 78]) ?><!--">--><?php //echo Yii::t('app', 'Pay Calculator') ?><!--</a>-->
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row top-table">
            <div class="col-7 cell">
                <?php echo Grid::widget([
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        'id',
                        'batch_date|date=' . Yii::$app->params['formats'][5] . '|filterable=true|visible=false',
                        new DataColumn([
                            'attribute' => 'status',
                            'value' => function (PayrollBatch $model) {
                                return Yii::t('app', $model->status);
                            },
                            'tags' => 'filterable=true|visible=false'
                        ]),
                        new DataColumn([
                            'attribute' => 'type',
                            'value' => function (PayrollBatch $model) {
                                return Yii::t('app', Inflector::pluralize(substr($model->type, 2)));
                            },
                            'tags' => 'filterable=true|visible=false'
                        ]),
                        'batch_date|date=' . Yii::$app->params['formats'][1],
                        'check_date|date=' . Yii::$app->params['formats'][1],
                        'period_start|date=' . Yii::$app->params['formats'][1],
                        'period_end|date=' . Yii::$app->params['formats'][1],
                        'type|abbreviation=app',
                        'status|abbreviation=app',
                        'id|visible=false',
                        'type|visible=false',
                        new DataColumn([
                            'title' => 'Finished',
                            'value' => function (PayrollBatch $model) {
                                return Yii::t('app', $model->status == PayrollBatchStatus::FINISHED ? 'Yes' : 'No');
                            },
                            'visible' => false
                        ]),
                        'posted|visible=false',
                        'unposted|visible=false',
                        'batch_date|visible=false|date=' . Yii::$app->params['formats'][0],
                        'period_start|visible=false|date=' . Yii::$app->params['formats'][0],
                        'period_end|visible=false|date=' . Yii::$app->params['formats'][0],
                        'check_date|visible=false|date=' . Yii::$app->params['formats'][0],
                        new DataColumn([
                            'title' => 'Ajax Url',
                            'visible' => false,
                            'value' => function (PayrollBatch $payrollBatch) {
                                return Url::toRoute(['payrolls', 'batchId' => $payrollBatch->id]);
                            }
                        ]),
                        new DataColumn([
                            'title' => 'Ajax Url',
                            'visible' => false,
                            'value' => function (PayrollBatch $payrollBatch) {
                                return Url::toRoute(['payroll/print', 'id' => $payrollBatch->id]);
                            }
                        ]),
                        new DataColumn([
                            'title' => 'Ajax Url',
                            'visible' => false,
                            'value' => function (PayrollBatch $payrollBatch) {
                                return Url::toRoute(['payroll/batch-summary', 'id' => $payrollBatch->id]);
                            }
                        ]),
                    ],
                    'template' => Yii::$app->params['dt']['templates'][0],
                    'order' => [[0, 'desc']],
                    'cssClass' => 'text-center',
                    'select' => [
                        'style' => 'single',
                        'items' => 'row'
                    ],
                    'id' => 'dt-payroll-batch',
                    'doubleClick' => ['modal', Url::toRoute(['update', 'id' => 'col:0'])],
                    // 'scrollY' => '200px',
                    // 'scrollCollapse' => true,
                    'paging' => false,
                    'colVis' => null
                ]); ?>
            </div>
            <div class="col-5 cell">
                <div class="card h-100">
                    <div class="card-body bp-0 py-1">
<!--       The new markup is starting                -->
                        <div class="row h-100 align-items-center" id="payroll-batch-details">
                            <div class="col">
                                <div class="row">
                                    <div class="col text-right">
                                        <p class="mb-0 font-weight-bold"><?php echo $dummyPayrollBatch->getAttributeLabel('id') ?></p>
                                        <p class="mb-0 font-weight-bold"><?php echo $dummyPayrollBatch->getAttributeLabel('type') ?></p>
                                        <p class="mb-0 font-weight-bold"><?php echo Yii::t('app', 'Finished') ?></p>
                                        <p class="mb-0 font-weight-bold"><?php echo $dummyPayrollBatch->getAttributeLabel('posted') ?></p>
                                        <p class="mb-0 font-weight-bold"><?php echo $dummyPayrollBatch->getAttributeLabel('unposted') ?></p>
                                    </div>
                                    <div class="col">
                                        <p class="mb-0"></p>
                                        <p class="mb-0"></p>
                                        <p class="mb-0"></p>
                                        <p class="mb-0"></p>
                                        <p class="mb-0"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row">
                                    <div class="col text-right">
                                        <p class="mb-0 font-weight-bold"></p>
                                        <p class="mb-0 font-weight-bold"><?php echo $dummyPayrollBatch->getAttributeLabel('batch_date') ?></p>
                                        <p class="mb-0 font-weight-bold"><?php echo $dummyPayrollBatch->getAttributeLabel('period_start') ?></p>
                                        <p class="mb-0 font-weight-bold"><?php echo $dummyPayrollBatch->getAttributeLabel('period_end') ?></p>
                                        <p class="mb-0 font-weight-bold"><?php echo $dummyPayrollBatch->getAttributeLabel('check_date') ?></p>
                                    </div>
                                    <div class="col">
                                        <p class="mb-0 js-ignore"></p>
                                        <p class="mb-0"></p>
                                        <p class="mb-0"></p>
                                        <p class="mb-0"></p>
                                        <p class="mb-0"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
<!--       The new markup is finishing               -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="mb-4 card shadow">
    <div class="card-header py-3">
        <div class="edit-form-toolbar">

        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-3 d-flex">
                <div class="dropdown">
                    <a href="#" class="btn btn-link dropdown-toggle" data-toggle="dropdown"><i class="fas fa-print"></i></a>
                    <div class="dropdown-menu">
                        <a href="#" class="dropdown-item js-ajax-modal" data-url="<?= Url::toRoute(['payroll/print']) ?>" id="print-1"><?= Yii::t('app', 'Settlements') ?></a>
                        <a href="#" class="dropdown-item js-ajax-modal" data-url="<?= Url::toRoute(['payroll/batch-summary']) ?>" id="batch-1"><?= Yii::t('app', 'Batch Summary') ?></a>
                    </div>
                </div>
            </div>
        </div>
        <?php echo Grid::widget([
            'ajax' => Url::toRoute(['payrolls', 'batchId' => 0]),
            'columns' => [
                'id|visible=false',
                'payroll_for|title=Payroll For|className=text-center',
                'cd|className=text-center|title=' . $dummyPayroll->getAttributeLabel('cd'),
                'cos|className=text-center|title=' . $dummyPayroll->getAttributeLabel('driver_type'),
                'off|className=text-center|title=' . $dummyPayroll->getAttributeLabel('office_id'),
                'load_pay|title=Load Pay|className=text-right',
                'miles_pay|title=Miles Pay|className=text-right',
                'other|title=Other|className=text-right',
                'net_amt|title=Net Amt|className=text-right',
                'posted|className=text-center|title=Posted',
                'period_start|className=text-center|title=' . $dummyPayrollBatch->getAttributeLabel('period_start'),
                'period_end|className=text-center|title=' . $dummyPayrollBatch->getAttributeLabel('period_end'),
                'status|title=Status|filterable=false|visible=false'
            ],
            'template' => Yii::$app->params['dt']['templates'][0],
            'order' => [[1, 'asc']],
            'id' => 'dt-payroll',
            'colReorder' => null,
            'colVis' => null,
            'searching' => false,
            'paging' => false,
            'select' => [
                'style' => 'single',
                'items' => 'row'
            ],
            'doubleClick' => ['modal', Url::toRoute(['pay-calculator/view', 'id' => 'col:0'])]
        ]); ?>
    </div>
</div>