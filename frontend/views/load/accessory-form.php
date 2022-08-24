<?php

use common\models\AccessorialMatrix;
use common\models\AccessorialPay;
use common\models\LoadAccessory;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\View;

/**
 * @var LoadAccessory $model
 * @var View $this ;
 */

$load = $model->load_id;
$updateAccessoriesUrl = Url::to(['load/ajax-list-accessories']);
$updateAccessoryRateUrl = Url::to(['load/ajax-accessory-rate']);

$updateAccessoriesList = <<<ENDJS
function updateAccessoriesList() {    
    var matrix = $(this).val();
    $.ajax({
        url: '{$updateAccessoriesUrl}',
        data: {'load': {$load}, 'matrix': matrix},
        success: function(response) {
            accSelect = $("#loadaccessory-accessorial_id");
            accSelect.find('option').remove(); 
            var listItems = '<option>Select</option>';
            $.each(response, function(key, val) {
                listItems += '<option value="' + val.id + '">' + val.label + '</option>';                 
            });
            accSelect.append(listItems);            
        }
    });
}

function updateRateByAccessory() {
    var accessory = $(this).val();
    $.ajax({
        url: '{$updateAccessoryRateUrl}',
        data: {'accessory': accessory},
        success: function(response) {
            $("#loadaccessory-rate_each").val(response);
            updateTotals();            
        }
    });
}

function updateTotals() {
    var rate = $("#loadaccessory-rate_each").val();
    var units = $("#loadaccessory-units").val();
    $("#loadaccessory-amount").val(rate * units);
}

$("#loadaccessory-matrix_id").change(updateAccessoriesList);
$("#loadaccessory-accessorial_id").change(updateRateByAccessory);
$("#loadaccessory-rate_each").change(updateTotals);
$("#loadaccessory-units").change(updateTotals);
ENDJS;

$this->registerJs($updateAccessoriesList, View::POS_READY);
$this->beginBlock('form');
?>
<?php $form = ActiveForm::begin([
    'id' => 'loadAccessory',
    'layout' => 'horizontal',
    'enableClientValidation' => false,
    'errorSummaryCssClass' => 'error-summary alert alert-danger',
    'fieldConfig' => Yii::$app->params['activeForm']['horizontalFormConfig'],
]); ?>

    <div class="form-fieldset">
        <div class="row">
            <div class="col-4"><?= Yii::t('app', 'Load') ?> <?= $model->load_id ?></div>
            <div class="col-4"><?= Yii::t('app', 'Invoice') ?></div>
            <div class="col-4"><?= Yii::t('app', 'Pay Drivers') ?> [&nbsp;&nbsp;]</div>
        </div>
    </div>

    <div class="form-fieldset">
        <div class="row">
            <div class="col-7">

                <?= $form->field($model, 'matrix_id')->dropDownList(ArrayHelper::map(AccessorialMatrix::find()->where(['inactive' => false])->all(), 'id', '_label'), ['prompt' => 'Select']); ?>

                <?php if ($model->matrix_id) : ?>
                    <?php // $matrix = AccessorialMatrix::findOne(['id' => $model->matrix_id]); ?>
                    <?php // $matrixNo = $matrix->matrix_no; ?>
                    <?php $accessorials = AccessorialPay::find()->joinWith('accessorialRating')->joinWith('accessorialRating.accessorialRatingMatrix')->where(['accessorial_matrix.id' => $model->matrix_id])->all() ?>
                    <?= $form->field($model, 'accessorial_id')->dropDownList(ArrayHelper::map($accessorials, 'id', 'accessorialRating.description'), ['prompt' => Yii::t('app', 'Select')]); ?>
                <?php else: ?>
                    <?= $form->field($model, 'accessorial_id')->dropDownList([], ['prompt' => Yii::t('app', 'Select')]); ?>
                <?php endif; ?>

                <?= $form->field($model, 'reference')->textInput(); ?>
            </div>
            <div class="col-5">
                <?= $form->field($model, 'rate_each', ['horizontalCssClasses' => ['label' => 'col-sm-3', 'wrapper' => 'col-sm-7']])->textInput(['type' => 'number', 'step' => 0.01]); ?>
                <?= $form->field($model, 'units', ['horizontalCssClasses' => ['label' => 'col-sm-3', 'wrapper' => 'col-sm-7']])->textInput(['type' => 'number', 'min' => 0]); ?>
                <?= $form->field($model, 'amount', ['horizontalCssClasses' => ['label' => 'col-sm-3', 'wrapper' => 'col-sm-7']])->textInput(['type' => 'number', 'step' => 0.01]); ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-8">
            <?= $form->field($model, 'notes', ['horizontalCssClasses' => ['label' => 'col-sm-2', 'wrapper' => 'col-sm-9']])->textarea(['rows' => 5]); ?>
        </div>
    </div>

<?php ActiveForm::end() ?>
<?php
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Book Accessorial'),
    'content' => $this->blocks['form'],
    'dialogCssClass' => 'modal-xl'
]);