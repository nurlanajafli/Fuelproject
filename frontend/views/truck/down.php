<?php
/**
 * @var \yii\web\View $this
 * @var array $formConfig
 * @var \frontend\forms\truck\Down $model
 */

use yii\bootstrap4\ActiveForm;
use common\widgets\ModalForm\ModalForm;
use yii\helpers\ArrayHelper;
use common\models\Location;

$this->beginBlock('content');
$formConfig['options']['class'] .= ' form row';
$form = ActiveForm::begin($formConfig);
/** @var \common\models\Truck $truckModel */
$truckModel = $model->getTruckModel();
$none = Yii::t('app', 'None');
$createdAt = substr($truckModel->created_at, 5, 2) . '/' . substr($truckModel->created_at, 8, 2) . '/' . substr($truckModel->created_at, 0, 4);
$info = [
    ['Truck', $truckModel->id],
    ['Unit', $none],
    ['Office', $truckModel->office ? $truckModel->office->office : $none],
    ['Status', Yii::t('app', $truckModel->status), 'mb-4'],
    ['Last Trip', ''],
    ['Load No', ''],
    ['Date', $createdAt],
];
$modalOptions = [
    'saveButton' => [
        'html' => Yii::t('app', 'Mark As Down') . '<i class="fas fa-arrow-down pl-2"></i>'
    ],
];
if ($truckModel->is_down) {
    $downedAt = substr($truckModel->downed_at, 5, 2) . '/' . substr($truckModel->downed_at, 8, 2) . '/' . substr($truckModel->downed_at, 0, 4);
    array_push($info, ['Posted', $downedAt]);
    array_push($info, ['By', $truckModel->downedBy->id]);
    $modalOptions = [
        'beforeSaveButtonHtml' => '<button class="btn btn-primary" type="button" data-id="post_updates">'.Yii::t('app', 'Post Updates') . '<i class="fas fa-arrow-down pl-2"></i></button>',
        'saveButton' => [
            'html' => Yii::t('app', 'Return To Service') . '<i class="fas fa-arrow-up pl-2"></i>',
            'data-id' => 'return_to_service'
        ],
    ];
}
?>
              <div class="col-5">
                <div class="form-fieldset">
                  <?php foreach ($info as $value): ?>
                  <div class="field row<?= isset($value[2]) ? " {$value[2]}" : '' ?>">
                    <div class="col">
                      <p><?= Yii::t('app', $value[0]) ?></p>
                    </div>
                    <div class="col">
                      <p class="form-readonly-text"><?= $value[1] ?></p>
                    </div>
                  </div>
                  <?php endforeach; ?>
                </div>
              </div>
              <div class="col-7">
                <div class="form-fieldset">
                  <?= $form->field($model, 'returnsToService', ['template' => '<div class="col">{label}</div><div class="col">{input}</div>', 'options' => ['class' => 'field row']])->textInput(['type' => 'date']) ?>
                  <?= $form->field($model, 'returnLocation', ['options' => ['tag' => false]])->dropdownList(
                      ArrayHelper::map(Location::find()->all(), 'id', 'location_name'),
                      ['prompt' => Yii::t('app', 'Select')]
                  ) ?>
                </div>
                <div class="form-fieldset">
                  <div class="mb-3"></div>
                </div>
                <div class="form-fieldset"><span class="form-legend"><?= Yii::t('app', 'Send Notifications') ?></span>
                  <?= $form->field($model, 'notifyAllDispatchPersonnel', ['options' => ['tag' => false]])->checkbox(['template' => '<div class="custom-control custom-checkbox">{input}{label}{error}</div>']) ?>
                </div>
              </div>
<?php
ActiveForm::end();
$this->endBlock();
echo ModalForm::widget([
    'title' => Yii::t('app', 'Take Truck Down') . " - {$truckModel->id}",
    'content' => $this->blocks['content'],
    'options' => array_merge([
        'dialog' => [
            'class' => 'modal-lg modal-dialog-centered'
        ],
        'closeButton' => false,
    ], $modalOptions)
]);