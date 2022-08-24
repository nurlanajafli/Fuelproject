<?php
/**
 * @var $model CarrierContact
 */

use common\models\CarrierContact;
use common\models\Department;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

$this->beginBlock('form');
?>
    <style>
        div.help-block {
            color: red
        }

        label {
            white-space: nowrap
        }
    </style>
<?php $form = ActiveForm::begin([
    'id' => 'customer-contact',
    'layout' => 'horizontal',
    'options' => ['method' => 'post', 'class' => 'form', 'enableAjaxValidation' => true],
    'fieldConfig' => Yii::$app->params['activeForm']['horizontalContactFormConfig'],
]); ?>
<?= $form->field($model, 'carrier_id', ['options' => ['tag' => false]])->hiddenInput()->label(false); ?>
    <div class="col-6">
        <?= $form->field($model, 'contact_name')->textInput(); ?>
        <?= $form->field($model, 'department_id')->dropDownList(
            ArrayHelper::map(Department::find()->all(), 'id', '_label'),
            ['prompt' => Yii::t('app', 'Select')]
        ); ?>
        <?= $form->field($model, 'direct_line')->textInput() ?>
        <?= $form->field($model, 'direct_fax')->textInput(); ?>
        <?= $form->field($model, 'extension')->textInput(); ?>
    </div>
    <div class="col-6">
        <?= $form->field($model, 'alt_phone_1')->textInput(); ?>
        <?= $form->field($model, 'desc_1')->textInput(); ?>
        <?= $form->field($model, 'alt_phone_2')->textInput(); ?>
        <?= $form->field($model, 'desc_2')->textInput(); ?>
        <?= $form->field($model, 'email')->textInput(); ?>
    </div>
    <div class="col-12">
        <?= $form->field($model, 'notes', ['horizontalCssClasses' => ['label' => 'col-sm-2', 'wrapper' => 'col-sm-9']])->textarea(['rows' => 5]); ?>
    </div>
    <div class="col-12"></div>
<?php ActiveForm::end() ?>
<?php
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', $model->id ? 'Edit contact' : 'Create contact'),
    'content' => $this->blocks['form'],
    'dialogCssClass' => 'modal-xl'
]);
