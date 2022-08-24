<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\FuelProductCode $model
 * @var array $formConfig
*/

use yii\bootstrap4\ActiveForm;
use common\widgets\tdd\Dropdown;
use yii\helpers\ArrayHelper;

$this->beginBlock('modal');
$form = ActiveForm::begin(ArrayHelper::merge($formConfig, [
    'options' => ['data-cb' => 'refreshSetupProductCodes']
]));
echo $form->field($model, 'oo_acct')->widget(Dropdown::class, $widgetConfig = [
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
        'order' => [[0, 'asc']]
    ],
    'displayColumnIndex' => 0
])->label('O/O Acct');
echo $form->field($model, 'cd_acct')->widget(Dropdown::class, $widgetConfig)->label('C/D Acct');
echo $form->field($model, 'fee_amt')->textInput(['type' => 'number', 'min' => 0, 'step' => 1]);
echo $form->field($model, 'fee_acct')->widget(Dropdown::class, $widgetConfig);
ActiveForm::end();
$this->endBlock();
echo \common\widgets\ModalForm\ModalForm::widget([
    'dialogCssClass' => 'modal-lg',
    'content' => $this->blocks['modal'],
    'title' => Yii::t('app', "Update Product Code - $model->description"),
]);
