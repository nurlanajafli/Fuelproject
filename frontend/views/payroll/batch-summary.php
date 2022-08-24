<?php
/**
 * @var \common\components\View $this
 * @var \frontend\forms\payroll\batch-summary $model
 * @var array $formConfig
 * @var array $gridConfig
 * @var boolean $saveBtn
 * @var boolean $deleteBtn
 */

use common\models\PayrollBatch;
use common\models\Payroll;
use yii\bootstrap4\ActiveForm;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use common\widgets\DataTables\Grid;
use yii\helpers\Url;
$dummyPayrollBatch = PayrollBatch::findOne($filter->batchId);
$this->beginBlock('tb'); ?>
    <div class="edit-form-toolbar">
        <button class="btn btn-link js-ajax-modal" id="exportUrl" title="<?=Yii::t('app', 'Export data') ?>" data-tooltip="tooltip" data-placement="top" data-tempurl="<?= Url::toRoute(['batch-convert']);?>" data-url="<?= Url::toRoute(['batch-convert','id'=>$filter->batchId]) ?>" data-id="convert">
            <i class="fas fa-arrow-up"></i>
        </button>
    </div>
<?php $this->endBlock(); ?>

<?php $this->beginBlock('batch-table'); ?>
    <div class="row">
        <div class="col-lg">
            <div id="payroll-batchsummary-toolbar__wrapper" data-url="<?= Url::toRoute('index-batch') ?>">
                <?php
                    $form = ActiveForm::begin([
                        'enableClientValidation' => false,
                        'enableAjaxValidation' => false
                    ]);
                    echo '<div class="dt-toolbar">';
                    echo $form->field($filter, 'batchId')->dropdownList(ArrayHelper::map(PayrollBatch::find()->all(), 'id', 'id'), [
                        'class' => 'custom-select'
                    ]);
                    echo '</div>';
                    ActiveForm::end();
                ?>
                <div class="row">
                    <div class="col-lg w-100">
                        <h3 class="h3 mb-0 text-gray-800"><?=Yii::t('app', 'Payroll summary') ?></h3>
                        <p><?=date('m/d/Y')?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg w-100">
                        <p>
                            <span class="m-mini"><?=Yii::t('app', 'From') ?></span>
                            <span class="m-mid fromData"><?=Yii::$app->formatter->asDate($dummyPayrollBatch->period_start)?></span>
                            <span class="m-mini"><?=Yii::t('app', 'To') ?></span>
                            <span class="m-mid toData"><?=Yii::$app->formatter->asDate($dummyPayrollBatch->period_end)?></span>
                            <span class="m-mini"><?=Yii::t('app', 'Check date') ?></span>
                            <span class="m-mid checkDate"><?=Yii::$app->formatter->asDate($dummyPayrollBatch->check_date)?></span>
                            <span class="m-mini"><?=Yii::t('app', 'Batch No') ?></span>
                            <span class="m-mid bNo"><?=$filter->batchId?></span>
                            <span class="m-mini"><?=Yii::t('app', 'Type') ?></span>
                            <span class="bType"><?=$dummyPayrollBatch->type?></span>
                        </p>
                    </div>
                </div>
                <?php
                echo Grid::widget([
                        'ajax' => Url::toRoute(['index-batch', $filter->formName() . '[batchId]' => $filter->batchId]),
                        'dataProvider' => new ArrayDataProvider([
                            'modelClass' => Payroll::class
                        ]),
                        'columns' => [
                            'id|visible=false',
                            'payrollFor|title=Payroll For',
                            'payableTo|title=Pay To',
                            'netAmount|title=Gross|className=text-right',
                            'perDiem|title=Per Diem',
                            'state|title=State',
                            'federal|title=Federal',
                            'fica|title=FICA',
                            'medicare|title=Medicare',
                            'deductions|title=Other|className=text-right',
                            'periodStart|title=From|className=fromCell d-none',
                            'periodEnd|title=To|className=toCell d-none',
                            'checkDate|title=Check Date|className=checkDateCell d-none',
                            'batchNo|title=Batch No|className=batchNoCell d-none',
                            'type|title=Type|className=typeCell d-none',
                            'totalWages|title=Check Amt|className=checkAmount text-right',
                        ],
                        'colVis' => false,
                        'colReorder' => null,
                        'info' => true,
                        'paging' => false,
                        'searching' => false,
                        'id' => 'dt-payroll-batchsummary',
                        'order' => [[0, 'asc']],
                        'template' => Yii::$app->params['dt']['templates'][0],
                        'orderCellsTop' => true,
                        'conditionalPaging' => true,
                        'autoWidth' => false
                    ]);
                ?>
            </div>
        </div>
    </div>

<?php
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Batch Summary'),
    'toolbar' => $this->blocks['tb'],
    'content' => $this->blocks['batch-table'],
    'dialogCssClass' => 'modal-xl',
    'options' => [
        'closeButton' => ['caption' => 'Cancel'],
        'saveButton' => false
        ],
]);
?>
<style>
    .m-mini {margin:0 6px 0 0;}
    .m-mid {margin:0 18px 0 0;}
</style>
