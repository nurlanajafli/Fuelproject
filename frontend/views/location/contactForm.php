<?php
/**
 * /var/www/html/frontend/runtime/giiant/4b7e79a8340461fe629a6ac612644d03
 *
 * @package default
 */

use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;

/**
 *
 * @var yii\web\View $this
 * @var common\models\LocationContact $model
 */

$this->beginBlock('form');
$form = ActiveForm::begin([
        'id' => 'location-contact',
        'layout' => 'horizontal',
        'options' => ['method' => 'post', 'class' => 'form', 'enableAjaxValidation' => true],
        'errorSummaryCssClass' => 'error-summary alert alert-danger',
        'fieldConfig' => Yii::$app->params['activeForm']['horizontalContactFormConfig'],
    ]
);
?>
<?= $form->field($model, 'location_id', ['options' => ['tag' => false]])->hiddenInput()->label(false) ?>
    <div class="col-6">
        <?= $form->field($model, 'contact_name')->textInput() ?>
        <?= $form->field($model, 'department_id')->dropdownList(
            ArrayHelper::map(\common\models\Department::find()->all(), 'id', '_label'),
            [
                'prompt' => Yii::t('app', 'Select'),
                'class' => 'custom-select'
            ]
        ) ?>
        <?= $form->field($model, 'direct_line')->textInput() ?>
        <?= $form->field($model, 'direct_fax')->textInput() ?>
        <?= $form->field($model, 'extension')->textInput() ?>
    </div>
    <div class="col-6">
        <?= $form->field($model, 'alt_phone_1')->textInput() ?>
        <?= $form->field($model, 'desc_1')->textInput() ?>
        <?= $form->field($model, 'alt_phone_2')->textInput() ?>
        <?= $form->field($model, 'desc_2')->textInput() ?>
        <?= $form->field($model, 'email')->textInput(['type' => 'email']) ?>
    </div>
    <div class="col-12">
        <?= $form->field($model, 'notes', [
            'horizontalCssClasses' => ['label' => 'col-sm-2', 'wrapper' => 'col-sm-9']
        ])->textarea(['rows' => 5]) ?>
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
