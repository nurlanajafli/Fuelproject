<?php

use common\enums\LoadStatus;
use yii\bootstrap4\ActiveForm;

/**
 * @var common\models\Load $model
 */

$this->beginBlock('form');
?>
<?php $form = ActiveForm::begin([
    'layout' => 'horizontal',
    'fieldConfig' => Yii::$app->params['activeForm']['horizontalLoadFormConfig'],
]); ?>
    <div class="card mb-1">
        <div class="card-body">
            <?= $form->field($model, 'status')->inline(true)->radioList([
                LoadStatus::AVAILABLE => LoadStatus::AVAILABLE,
                LoadStatus::POSSIBLE => LoadStatus::POSSIBLE,
                LoadStatus::PENDING => LoadStatus::PENDING
            ]) ?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<?php
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Change status'),
    'content' => $this->blocks['form'],
    'dialogCssClass' => 'modal-lg'
]);
