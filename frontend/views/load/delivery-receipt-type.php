<?php
/**
 * @var \yii\web\View $this
 * @var \frontend\forms\load\DeliveryReceipt $form
 */

use common\enums\DeliveryReceiptTypes;
use yii\bootstrap4\ActiveForm;

$this->beginBlock('form');
$activeForm = ActiveForm::begin([
    'layout' => 'horizontal',
    'options' => ['target' => '_blank'],
    'fieldConfig' => Yii::$app->params['activeForm']['horizontalLoadFormConfig']
]); // appearance config
?>
  <div class="card mb-1">
    <div class="card-body">
        <?= $activeForm->field($form, 'type')->dropdownList(DeliveryReceiptTypes::getUiEnums()) ?>
    </div>
  </div>
<?php
ActiveForm::end();
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Select Delivery Receipt Type'),
    'content' => $this->blocks['form'],
    'dialogCssClass' => 'modal-lg',
    'saveButton' => Yii::t('app', 'Generate PDF'),
]);
