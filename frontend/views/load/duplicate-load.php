<?php

use common\models\Load;
use frontend\forms\load\DuplicateLoad;
use yii\bootstrap4\ActiveForm;

/**
 * @var ActiveForm $form
 * @var DuplicateLoad $duplicateLoad
 * @var Load $model
 */

$this->beginBlock('form');

$form = ActiveForm::begin([
    'id' => 'DuplicateLoad',
    'layout' => 'horizontal',
    'errorSummaryCssClass' => 'error-summary alert alert-danger',
]);
$form->fieldConfig = Yii::$app->params['activeForm']['horizontalLoadFormConfig'];
?>
    <div class="card mb-1">
        <div class="card-body">
            <div class="row">
                <div class="col-7">
                    <?= $form->field($duplicateLoad, 'dateReceived')->textInput(["type" => "date"]); ?>
                    <?= $form->field($duplicateLoad, 'pickupDate')->textInput(["type" => "date"]); ?>
                    <?= $form->field($duplicateLoad, 'copiesToMake')->textInput(["type" => "number"]); ?>
                </div>
                <div class="col-5">
                    <?= $form->field($duplicateLoad, 'copyImages')->checkbox(); ?>
                    <?= $form->field($duplicateLoad, 'copyLoadNotes')->checkbox(); ?>
                    <?= $form->field($duplicateLoad, 'copyReferenceNumbers')->checkbox(); ?>
                    <?= $form->field($duplicateLoad, 'postToFreightTracking')->checkbox(); ?>
                </div>
            </div>
        </div>
    </div>
<?php ActiveForm::end() ?>
<?php
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Duplicate loads'),
    'content' => $this->blocks['form'],
    'dialogCssClass' => 'modal-lg',
    'saveButton' => Yii::t('app', 'Process')
]);
