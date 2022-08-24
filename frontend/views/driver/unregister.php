<?php
/**
 * @var \yii\web\View $this
 * @var array $formConfig
 * @var \common\models\Driver $model
*/

use yii\bootstrap4\ActiveForm;
use common\widgets\ModalForm\ModalForm;

$this->beginBlock('content');
$formConfig['options']['class'] .= ' form-fieldset mb-0';
$form = ActiveForm::begin($formConfig);
$items = [
    'Works with any smart phone',
    '2-Way Dispatch Messaging',
    '2-Way Document Transfers',
    'Driver Submitted Check Calls',
    'Driver Submitted Load Status Updates',
    'GPS Verified Driver Location Tracking',
    'Document Image Capture and Transfer',
    'Delivery Signature Capture and Transfer'
];
echo $form->field($model, 'bugFix', ['options' => ['tag' => false]])->hiddenInput()->label(false);
?>
            <span class="form-legend"><?= Yii::t('app', 'Mobile App') ?></span>
              <div class="mer-modal-content">
                <div class="row">
                  <div class="col ml-4">
                    <p class="form-readonly-text"><?= Yii::t('app', 'Mobile App is an advanced system that enables full two-way communication between dispatchpersonnel and drivers. Mobile App is a fee based service, contact PCS Software directly for current pricing.') ?></p>
                    <ul>
                      <?php foreach ($items as $item): ?>
                      <li><?= Yii::t('app', $item) ?></li>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                </div>
              </div>
<?php
ActiveForm::end();
$this->endBlock();
echo ModalForm::widget([
    'title' => Yii::t('app', 'Mobile App Registration'),
    'content' => $this->blocks['content'],
    'cssClass' => 'mer-modal',
    'dialogCssClass' => 'modal-lg',
    'saveButton' => Yii::t('app', 'Unregister'),
    'closeButton' => false,
]);