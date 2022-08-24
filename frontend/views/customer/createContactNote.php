<?php

use common\enums\I18nCategory;
use yii\bootstrap4\ActiveForm;
use common\widgets\ModalForm\ModalForm;

/**
 * @var \yii\web\View $this
 * @var \frontend\forms\CustomerContactNote $model
 * @var array $formConfig
 * @var \common\models\Customer $customer
 */

$this->beginBlock('content');
$formConfig['options']['class'] .= ' form';
ModalForm::registerAjaxReloadCallback('#customer-notes-modal .js-datatable', $this, $formConfig);
$form = ActiveForm::begin($formConfig);
?>
    <div class="form-fieldset mb-2">
        <div class="form-group row align-items-center">
            <div class="col-2"><?= $model->getAttributeLabel('contact') ?></div>
            <?= $form->field($model, 'contact', ['options' => ['class' => 'col-5']])->textInput([
                'placeholder' => $model->getAttributeLabel('contact')
            ])->label(false) ?>
            <div class="col-5"><span class="text-primary"><?= $customer->name ?></span></div>
        </div>
        <div class="form-group row align-items-center">
            <div class="col-2"><?= $model->getAttributeLabel('code') ?></div>
            <?= $form->field($model, 'code', ['options' => ['class' => 'col-5']])->dropDownList(
                \yii\helpers\ArrayHelper::map(\common\models\CompanyNoteCode::find()->all(), 'code', 'code'),
                ['prompt' => Yii::t('app', 'Select')]
            )->label(false) ?>
            <div class="col-5"><span class="text-primary"></span></div>
        </div>
        <div class="form-group row">
            <div class="col-2"><?= $model->getAttributeLabel('notes') ?></div>
            <?= $form->field($model, 'notes', ['options' => ['class' => 'col-10']])->textarea([
                'rows' => 7
            ])->label(false) ?>
        </div>
        <div class="form-group row align-items-center">
            <div class="col-7 offset-2">
                <div class="form-row align-items-center">
                    <div class="col-3"><?= $model->getAttributeLabel('next_contact') ?></div>
                    <?= $form->field($model, 'next_contact_date', ['options' => ['class' => 'col-6']])->textInput([
//                        'placeholder' => '__/__/____',
//                        'data-mask' => '00/00/0000',
                        'type' => 'date'
                    ])->label(false) ?>
                    <?= $form->field($model, 'next_contact_time', ['options' => ['class' => 'col-3']])->textInput([
                        'placeholder' => '__:__',
                        'data-mask' => '00:00',
                        'maxlength' => 5
                    ])->label(false) ?>
                </div>
            </div>
            <?= $form->field($model, 'post_reminder', [
                'options' => ['class' => 'col-3']
            ])->checkbox(['template' => '<div class="custom-control custom-checkbox">{input}{label}{error}</div>']) ?>
        </div>
    </div>
<?php
ActiveForm::end();
$this->endBlock();
echo ModalForm::widget([
    'dialogCssClass' => 'modal-lg',
    'title' => Yii::t('app', 'Input Note'),
    'content' => $this->blocks['content']
]);