<?php
/**
 * @var \common\components\View $this
 * @var \common\models\FuelPurchase $model
 * @var array $formConfig
*/

echo \common\widgets\ModalForm\ModalForm::widget([
    'content' => $this->render('_form', ['model' => $model, 'formConfig' => $formConfig]),
    'dialogCssClass' => 'modal-xl',
    'title' => Yii::t('app', 'Input Fuel Purchase')
]);