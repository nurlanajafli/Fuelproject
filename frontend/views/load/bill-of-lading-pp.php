<?php

use common\enums\BillOfLadingType;
use yii\bootstrap4\ActiveForm;

/**
 * @var common\models\Load $model
 */
$billTypesArray = BillOfLadingType::getUiEnums();
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
]);
 ?>
    <div class="card mb-1">
        <div class="card-body">
            <?= $activeform->field($form, 'freightChargesType')->dropDownList($arrFilterFunc($billTypesArray,[BillOfLadingType::SHOW_FREIGHT_CHARGES,BillOfLadingType::HIDE_FREIGHT_CHARGES]))?>
        </div>
        <div class="card-body">
            <?= $activeform->field($form, 'viewType')->dropDownList($arrFilterFunc($billTypesArray,[BillOfLadingType::STANDART_VIEW,BillOfLadingType::PREMIUM_VIEW]))?>
        </div>
        <div class="card-body">
            <?= $activeform->field($form, 'billingNoticeType')->dropDownList($arrFilterFunc($billTypesArray,[BillOfLadingType::SHOW_BILLING_NOTICE,BillOfLadingType::HIDE_BILLING_NOTICE]))?>
        </div>
        <div class="card-body">
            <?= $activeform->field($form, 'carrierNameType')->dropDownList($arrFilterFunc($billTypesArray,[BillOfLadingType::SHOW_CARRIER_NAME,BillOfLadingType::HIDE_CARRIER_NAME]))?>
        </div>
        <div class="card-body">
            <?= $activeform->field($form, 'phoneNumbersType')->dropDownList($arrFilterFunc($billTypesArray,[BillOfLadingType::SHOW_PHONE_NUMBERS,BillOfLadingType::HIDE_PHONE_NUMBERS]))?>
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