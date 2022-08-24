<?php

use yii\bootstrap4\ActiveForm;
use common\enums\BatchConvertFormat;

$batchConvertFormat = BatchConvertFormat::getEnums();
$this->beginBlock('batch-form');
$activeform = ActiveForm::begin([
    'layout' => 'horizontal',
    'fieldConfig' => Yii::$app->params['activeForm']['horizontalLoadFormConfig'],
]); ?>
    <div class="row convertForm">
        <div class="col-lg">
            <?= $activeform->field($form,'batch_id')->hiddenInput()->label(false); ?>
            <?= $activeform->field($form, 'convertFormat')->dropDownList($batchConvertFormat);?>
        </div>
    </div>
<?php
ActiveForm::end();
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Batch Convert To'),
    'toolbar' => $this->blocks['tb'],
    'content' => $this->blocks['batch-form'],
    'dialogCssClass' => 'modal-xl',
    'bodyCssClass' => 'convertForm',
    'saveButton' => false,
    'options' => [
        'closeButton' => ['caption' => 'Cancel'],
        'saveButton' => ['caption'=>'Convert']
    ],
]); ?>



