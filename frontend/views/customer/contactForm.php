<?php
/**
 * @var $model CustomerContact
 */

use common\models\CustomerContact;
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
<?= $form->field($model, 'customer_id', ['options' => ['tag' => false]])->hiddenInput()->label(false); ?>
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
    <div class="col-12">
        <div class="form-group row">
            <label class="col-2"></label>
            <div class="col-9 mt-2">
                <div class="form-fieldset">
                    <span class="form-legend"><?= Yii::t('app', 'Status Update Notification Options') ?></span>
                    <div class="form-check form-check-inline">
                        <?= $form->field($model, 'all_updates', ['options' => ['tag' => false, 'template' => '{input}']])->checkbox(); ?>
                    </div>
                    <div class="form-check form-check-inline">
                        <?= $form->field($model, 'booked', ['options' => ['tag' => false, 'template' => '{input}']])->checkbox(); ?>
                    </div>
                    <div class="form-check form-check-inline">
                        <?= $form->field($model, 'at_origin', ['options' => ['tag' => false, 'template' => '{input}']])->checkbox(); ?>
                    </div>
                    <div class="form-check form-check-inline">
                        <?= $form->field($model, 'delivered', ['options' => ['tag' => false, 'template' => '{input}']])->checkbox(); ?>
                    </div>
                </div>
            </div>
        </div>
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
