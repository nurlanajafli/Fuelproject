<?php
/**
 * @var \yii\web\View $this
 * @var \common\models\LoadStop $model
 * @var array $formConfig
 */

echo \common\widgets\ModalForm\ModalForm::widget([
    'content' => $this->render('_form', ['model' => $model, 'formConfig' => $formConfig]),
    'dialogCssClass' => 'modal-xl',
    'title' => Yii::t('app', 'Add Stop'),
]);