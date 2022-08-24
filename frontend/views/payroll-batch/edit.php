<?php
/**
 * @var \common\components\View $this
 * @var \frontend\forms\payrollBatch\Edit $model
 * @var array $formConfig
 * @var array $gridConfig
 * @var boolean $saveBtn
 * @var boolean $deleteBtn
 */

use common\helpers\Utils;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use common\widgets\DataTables\Grid;
use yii\helpers\Url;
use common\enums\PayrollBatchStatus;

$payrollBatch = $model->getPayrollBatch();
$this->beginBlock('tb');
?>
<div class="edit-form-toolbar">
    <?php if ($payrollBatch->id && ($payrollBatch->status == PayrollBatchStatus::UNFINISHED)) : ?>
        <a class="btn btn-link" href="<?php echo Url::toRoute(['payroll-batch/finish', 'id' => $payrollBatch->id]) ?>" data-tooltip="tooltip" data-placement="top">
            <i class="fas fa-check"></i>
        </a>
    <?php endif; ?>
    <?php if ($saveBtn) : ?>
    <button class="btn btn-link js-submit" data-tooltip="tooltip" data-placement="top" data-id="save">
        <i class="fas fa-save"></i>
    </button>
    <?php endif; ?>
    <?php if ($deleteBtn) : ?>
    <a class="btn btn-link" href="<?php echo Url::toRoute(['payroll-batch/delete', 'id' => $payrollBatch->id]) ?>" data-tooltip="tooltip" data-placement="top">
        <i class="fas fa-trash-alt"></i>
    </a>
    <?php endif; ?>
</div>
<?php
$this->endBlock();
$this->beginBlock('form');
$form = ActiveForm::begin(ArrayHelper::merge($formConfig, [
    'fieldConfig' => [
        'options' => ['class' => 'form-group row'],
        'template' => '<div class="col-sm-4">{label}</div><div class="col-sm-8">{input}{error}</div>'
    ]
]));
echo $form->field($model, 'ids', ['options' => ['class' => 'd-none']])->dropdownList(ArrayHelper::map($gridConfig['dataProvider']->getModels(), 'id', 'id'), ['multiple' => 'multiple']);
?>
    <div class="row">
        <div class="col-4">
            <?php if ($payrollBatch->id) : ?>
            <div class="row">
                <div class="col-sm-4">
                    <strong><?php echo Yii::t('app', 'Batch') ?></strong>
                </div>
                <div class="col-sm-5"><?php echo $payrollBatch->id ?></div>
            </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-sm-4">
                    <strong><?php echo Yii::t('app', 'Type') ?></strong>
                </div>
                <div class="col-sm-5"><?php echo Utils::abbreviation($payrollBatch->type, 'app') ?></div>
            </div>
            <div class="row mb-3">
                <div class="col-sm-4">
                    <?php if ($payrollBatch->status == PayrollBatchStatus::FINISHED) : ?>
                    <strong><?php echo Yii::t('app', 'Finished') ?></strong>
                    <?php endif; if ($payrollBatch->status == PayrollBatchStatus::UNFINISHED) : ?>
                        <strong><?php echo Yii::t('app', 'Unfinished') ?></strong>
                    <?php endif; ?>
                </div>
            </div>
            <?php echo $form->field($model, 'batchDate')->textInput(['type' => 'date']) ?>
            <?php echo $form->field($model, 'periodStart')->textInput(['type' => 'date']) ?>
            <?php echo $form->field($model, 'periodEnd')->textInput(['type' => 'date']) ?>
            <?php echo $form->field($model, 'checkDate')->textInput(['type' => 'date']) ?>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-body">
                    <?php echo Grid::widget($gridConfig) ?>
                </div>
            </div>
        </div>
    </div>
<?php
ActiveForm::end();
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Batch Info'),
    'toolbar' => $this->blocks['tb'],
    'content' => $this->blocks['form'],
    'dialogCssClass' => 'modal-xl',
    'saveButton' => false,
    'closeButton' => false
]);
