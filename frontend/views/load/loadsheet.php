<?php

use common\enums\LoadsheetType;
use yii\bootstrap4\ActiveForm;

/**
 * @var common\models\Load $model
 */
$loadTypesArray = LoadsheetType::getUiEnums();
$arrFilterFunc = function($baseArray,$allowedArray) {
    $result = [];
    foreach ($baseArray as $key => $value) {
        if (in_array($key, $allowedArray)) {
            $result[$key] = $value;
        }
    }
    return $result;
};
$this->beginBlock('form');
$activeform = ActiveForm::begin([
    'layout' => 'horizontal',
    'options'=>['target'=>'_blank'],
    'fieldConfig' => Yii::$app->params['activeForm']['horizontalLoadFormConfig'],
]); ?>
    <div class="card mb-1">
        <div class="card-body">
            <?= $activeform->field($form, 'revenue')->dropDownList($arrFilterFunc($loadTypesArray,[LoadsheetType::SHOW_ALL_REVENUE,LoadsheetType::HIDE_ALL_REVENUE,LoadsheetType::HIDE_ACROSSORIAL_REVENUE]))?>
        </div>
        <div class="card-body">
            <?= $activeform->field($form, 'directions')->dropDownList($arrFilterFunc($loadTypesArray,[LoadsheetType::SHOW_DIRECTIONS,LoadsheetType::HIDE_DIRECTIONS]))?>
        </div>
        <div class="card-body">
            <?= $activeform->field($form, 'stopNotes')->dropDownList($arrFilterFunc($loadTypesArray,[LoadsheetType::SHOW_STOP_NOTES,LoadsheetType::HIDE_STOP_NOTES]))?>
        </div>
    </div>
<?php ActiveForm::end(); ?>
<?php
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Select'),
    'content' => $this->blocks['form'],
    'dialogCssClass' => 'modal-lg',
    'saveButton' => 'Generate PDF',
]);
?>