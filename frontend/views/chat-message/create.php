<?php
/**
 * @var \yii\web\View $this
 * @var \frontend\forms\chatMessage\Create $model
 * @var array $formConfig
 * @var string $fullName
 */

use yii\bootstrap4\ActiveForm;

$this->beginBlock('content');
$form = ActiveForm::begin($formConfig);
?>
    <div class="row">
        <div class="col px-5">
            <div class="field">
                <?= \yii\bootstrap4\Html::textInput('fullName', $fullName, ['readonly' => 'readonly', 'class' => 'form-control form-readonly-text']) ?>
            </div>
            <?= $form->field($model, 'message')->textarea(['rows' => 10]) ?>
        </div>
    </div>
<?php
ActiveForm::end();
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Send Message'),
    'cssClass' => 'send-modal',
    'dialogCssClass' => 'modal-lg',
    'content' => $this->blocks['content'],
    'saveButton' => Yii::t('app', 'Send')
]);
