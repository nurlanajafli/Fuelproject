<?php
/**
 * @var yii\web\View $this
 * @var common\models\Bill $model
 * @var array $formConfig
 */
?>
<?= \common\widgets\ModalForm\ModalForm::widget([
    'title' => Yii::t('app', 'Bill'),
    'content' => $this->render('_form', ['model' => $model, 'formConfig' => $formConfig]),
    'cssClass' => 'modal--no-scroll',
    'dialogCssClass' => 'modal-full',
    'toolbar' => $this->render('_toolbar', ['model' => $model])
]) ?>