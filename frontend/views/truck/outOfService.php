<?php
/**
 * @var \yii\web\View $this
 * @var mixed $model
 * @var array $formConfig
 */

use frontend\forms\truck\EditOutOfServiceStatus;
use frontend\forms\truck\OutOfService;
use yii\bootstrap4\ActiveForm;

$this->beginBlock('content');
$formConfig['options']['class'] .= ' form';
$form = ActiveForm::begin($formConfig);
$title = 'Truck Out of Service';
$dialogCssClass = 'modal-lg modal-dialog-centered';
if ($model instanceof OutOfService):
?>
    <div class="form-fieldset">
        <p class="text-secondary mb-5">No Unit Assigned</p>
        <?= $form->field($model, 'truck', ['options' => ['tag' => false], 'radioOptions' => ['class' => 'custom-control-input', 'labelOptions' => ['class' => 'custom-control-label'], 'wrapperOptions' => ['class' => 'field custom-control custom-radio']]])->radioList([
            OutOfService::REMOVE_FROM_UNIT => Yii::t('app', 'Remove Truck From Assigned Unit'),
            OutOfService::MAKE_INACTIVE => Yii::t('app', 'Make Assigned Unit Inactive')
        ])->label(false) ?>
        <div class="form-fieldset mt-5"><span class="form-legend"><?= Yii::t('app', 'Drivers') ?></span>
            <?= str_replace('role="radiogroup"', 'role="radiogroup" class="field row"', $form->field($model, 'drivers', ['options' => ['tag' => false], 'radioOptions' => ['class' => 'custom-control-input', 'labelOptions' => ['class' => 'custom-control-label'], 'wrapperOptions' => ['class' => 'col custom-control custom-radio']]])->radioList([
                OutOfService::REMOVE_FROM_UNIT => Yii::t('app', 'Remove From Unit'),
                OutOfService::KEEP_WITH_UNIT => Yii::t('app', 'Keep With Unit')
            ])->label(false)) ?>
        </div>
        <div class="form-fieldset"><span class="form-legend"><?= Yii::t('app', 'Trailers') ?></span>
            <?= str_replace('role="radiogroup"', 'role="radiogroup" class="field row"', $form->field($model, 'trailers', ['options' => ['tag' => false], 'radioOptions' => ['class' => 'custom-control-input', 'labelOptions' => ['class' => 'custom-control-label'], 'wrapperOptions' => ['class' => 'col custom-control custom-radio']]])->radioList([
                OutOfService::REMOVE_FROM_UNIT => Yii::t('app', 'Remove From Unit'),
                OutOfService::MAKE_INACTIVE => Yii::t('app', 'Make Inactive')
            ])->label(false)) ?>
        </div>
        <div class="row">
            <div class="col-6 m-auto">
                <div class="field row">
                    <div class="col">
                        <label>Out of Service</label>
                    </div>
                    <div class="col">
                        <input class="form-control" type="date" value="<?= date('Y-m-d') ?>" disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php else:
    $title = 'Set Date';
    $dialogCssClass = 'modal-dialog-centered';
?>
    <div class="row align-items-center">
        <div class="col-7">
            <?= $form->field($model, 'editOrRemoveDate', ['options' => ['tag' => false]])->radio([
                'template' => '<div class="field custom-control custom-radio">{input}{label}{error}</div>',
                'value' => EditOutOfServiceStatus::EDIT,
            ])->label(Yii::t('app', 'Edit Out of Service Date')) ?>
        </div>
        <div class="col-5">
            <?= $form->field($model, 'date', ['template' => '{input}', 'options' => ['class' => 'field']])->textInput(['type' => 'date']) ?>
        </div>
    </div>
    <div class="row align-items-center">
        <div class="col-7">
            <?= $form->field($model, 'editOrRemoveDate', ['options' => ['tag' => false]])->radio([
                'template' => '<div class="field custom-control custom-radio">{input}{label}{error}</div>',
                'value' => EditOutOfServiceStatus::REMOVE,
                'uncheck' => null,
                'id' => 'editoutofservicestatus-editorremovedate-2'
            ])->label(Yii::t('app', 'Remove Out of Service Date')) ?>
        </div>
    </div>
<?php endif; ?>
<?php
ActiveForm::end();
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', $title),
    'content' => $this->blocks['content'],
    'options' => [
        'dialog' => ['class' => $dialogCssClass],
        'closeButton' => ['caption' => 'Cancel'],
        'saveButton' => ['caption' => 'Save']
    ]
]);