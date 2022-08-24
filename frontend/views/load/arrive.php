<?php

/**
 * @var \yii\web\View $this
 * @var \common\models\Load $loadModel
 * @var \frontend\forms\load\Arrival $model
 * @var array $formConfig
*/

use yii\bootstrap4\ActiveForm;

$stops = $loadModel->getLoadStopsOrdered();
$lastStop = end($stops);
$formatter = Yii::$app->getFormatter();
$this->beginBlock('content');
$formConfig['options']['class'] .= ' form col';
$form = ActiveForm::begin($formConfig);
?>
<div class="col-12 border mb-3">
    <div class="row p-3">
        <div class="col-7">
            <div class="text-center pb-3 mb-4 border-bottom"><?= Yii::t('app', 'Actual') ?></div>
            <div class="row">
                <div class="col-5">
                    <?= $form->field($model, 'date')->textInput(['type' => 'date']) ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'in')->textInput(['type' => 'time']) ?>
                </div>
                <div class="col">
                    <?= $form->field($model, 'out')->textInput(['type' => 'time']) ?>
                </div>
                <div class="col-12">
                    <?= $form->field($model, 'signedForBy')->textInput() ?>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="text-center pb-3 mb-3 border-bottom"><?= Yii::t('app', 'Scheduled') ?></div>
            <div class="row px-3 p-1">
                <div class="col"><?= Yii::t('app', 'Date') ?><br><?= $formatter->asDate($lastStop->available_from) ?></div>
                <div class="col"><?= Yii::t('app', 'In') ?><br><?= $formatter->asTime($lastStop->time_from) ?></div>
                <div class="col"><?= Yii::t('app', 'Out') ?><br><?= $formatter->asTime($lastStop->time_to) ?></div>
            </div>
        </div>
    </div>
</div>
<div class="col border mb-1">
    <?= $form->field($model, 'trailerDisposition', ['options' => ['class' => 'form-group p-3']])->inline()->radioList($model->getTrailerOptions()) ?>
</div>
<div class="col border mb-1">
    <?= $form->field($model, 'postDeliveryOptions', ['options' => ['class' => 'form-group p-3']])->inline()->radioList($model->getUnitOptions()) ?>
</div>
<?php
ActiveForm::end();
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Arrive Load {n} TL', ['n' => $loadModel->id]),
    'content' => $this->blocks['content'],
    'options' => [
        'dialog' => ['class' => 'modal-xl'],
    ]
]);