<?php
/**
 * @var \yii\web\View $this
 * @var \frontend\forms\bill\AdjustBalance $model
 * @var array $formConfig
 * @var \common\models\Bill $bill
 */

use yii\bootstrap4\ActiveForm;
use common\widgets\tdd\Dropdown;
use \yii\widgets\Pjax;

$this->beginBlock('form');

Pjax::begin(['enablePushState' => false,]);

$form = ActiveForm::begin(\yii\helpers\ArrayHelper::merge($formConfig, [
    'fieldConfig' => [
        'options' => ['class' => 'row field'],
        'template' => '<div class="col-3">{label}</div><div class="col-5">{input}{error}</div>',
    ],
    'enableAjaxValidation' => false,
    'options' => ['data-pjax' => true, 'class' => '',],
    'id' => 'bill-adjustbalance',
]));

echo $form->field($model, 'date')->textInput(['type' => 'date']);

echo $form->field($model, 'glAccount')->widget(Dropdown::class, [
    'grid' => [
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => \common\models\Account::find(),
            'pagination' => false,
        ]),
        'columns' => [
            'account',
            'description|title=Desc',
            'account_type|rel=accountType.type'
        ],
        'order' => [[0, 'asc']],
    ],
    'displayColumnIndex' => 0,
]);

echo $form->field($model, 'office')->widget(Dropdown::class, [
    'grid' => [
        'dataProvider' => new \yii\data\ActiveDataProvider([
            'query' => \common\models\Office::find(),
            'pagination' => false,
        ]),
        'columns' => [
            'id',
            'office'
        ],
        'ordering' => false,
    ],
]);

echo $form->field($model, 'amount')->textInput(['type' => 'number', 'step' => '0.01']);

echo $form->field($model, 'ourRef')->textInput();

echo $form->field($model, 'udf')->textInput();

echo $form->field($model, 'memo', ['template' => '<div class="col-3">{label}</div><div class="col-9">{input}{error}</div>'])->textarea(['rows' => 5]);

?>

<div class="row field mt-4">
    <div class="col-6 text-center">
        <p><?= Yii::t('app', 'Amount Owned BEFORE Adjustment') ?></p>
        <p class="form-readonly-text"><?= Yii::$app->formatter->asDecimal($bill->balance) ?></p>
    </div>
    <div class="col-6 text-center">
        <p><?= Yii::t('app', 'Amount Owned AFTER Adjustment') ?></p>
        <p class="form-readonly-text"><?= Yii::$app->formatter->asDecimal($bill->balance - (is_numeric($model->amount) ? $model->amount : 0)) ?></p>
    </div>
</div>

<?php

ActiveForm::end();

Pjax::end();

$this->endBlock();

echo \common\widgets\ModalForm\ModalForm::widget([
    'id' => 'bill-adjustbalance-modal',
    'dialogCssClass' => 'modal-xs',
    'title' => Yii::t('app', 'Adjust Balance'),
    'content' => $this->blocks['form'],
]);
