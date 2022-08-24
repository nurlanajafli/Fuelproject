<?php


use common\models\Load;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var Load $model
 * @var View $this ;
 */

$load = $model->id;
$ajaxPreviewRatesUrl = Url::to(['load/ajax-preview-rates']);

$rateJs = <<<ENDJS
function updateValues() {
    var data = {
        'load': {$load},
        'rateBy': $("#load-rate_by").val(),
        'rate': $("#load-rate").val(),
        'discount': $("#load-discount_percent").val()
    };
    
    $.ajax({
        url: '{$ajaxPreviewRatesUrl}',
        type: 'get',
        data: data,
        success: function(response){       
            $("#gross").html(response.gross);
            $("#discount_amount").html(response.discount_amount);
            $("#freight").html(response.freight);
            $("#accessorials").html(response.accessorials);
            $("#total").html(response.total);
        }
    });
}

$("#load-rate_by").change(updateValues);
$("#load-rate").blur(updateValues);
$("#load-discount_percent").blur(updateValues);        
ENDJS;

$this->registerJs($rateJs, View::POS_READY);
$this->beginBlock('form');
?>
<?php $form = ActiveForm::begin([
    'id' => 'Load',
    'layout' => 'horizontal',
    'enableClientValidation' => false,
    'errorSummaryCssClass' => 'error-summary alert alert-danger',
    'fieldConfig' => Yii::$app->params['activeForm']['horizontalFormConfig'],
]); ?>
<?php $form->fieldConfig = Yii::$app->params['activeForm']['horizontalContactFormConfig'] ?>
<?= $form->field($model, 'rate_source')->dropDownList(\common\enums\RateSource::getUiEnums(), ['readonly' => true, 'disabled' => true]); ?>
    <hr>
    <div class="d-flex">
        <div class="col-5">
            <div class="form-fieldset">
                <?= $form->field($model, 'rate_by')->dropDownList(\common\enums\RateBy::getUiEnums()); ?>
                <?= $form->field($model, 'rate')->textInput(['type' => 'number', 'min' => 0, 'step' => 0.01]); ?>
                <?= $form->field($model, 'units')->textInput(['type' => 'number', 'min' => 0, 'step' => 0.01, 'readonly' => true]); ?>
                <div class="form-group row">
                    <label class="col-sm-4"><?= Yii::t('app', 'Gross') ?></label>
                    <p id="gross" class="col-sm-6"><?= $model->gross ?></p>
                </div>
            </div>
        </div>
        <div class="col-5">
            <div class="form-fieldset">
                <?= $form->field($model, 'discount_percent')->textInput(['type' => 'number', 'min' => 0, 'step' => 0.01]); ?>
                <div class="form-group row">
                    <label class="col-sm-4"><?= Yii::t('app', 'Discount Amt') ?></label>
                    <p id="discount_amount" class="col-sm-6"><?= $model->discount_percent ?></p>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4"><?= Yii::t('app', 'Freight Rev') ?></label>
                    <p id="freight" class="col-sm-6"><?= $model->freight ?></p>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4"><?= Yii::t('app', 'Accessorials') ?></label>
                    <p id="accessorials" class="col-sm-6"><?= $model->accessories ?></p>
                </div>
            </div>
        </div>
        <div class="col-2">
            <?= Yii::t('app', 'Total Revenue') ?>
            <p id="total" class="col-sm-6"><?= $model->total ?></p>
        </div>
    </div>
<?php ActiveForm::end() ?>
<?php
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Rate Load'),
    'content' => $this->blocks['form'],
    'dialogCssClass' => 'modal-xl'
]);