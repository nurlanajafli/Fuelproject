<?php
/**
 * /var/www/html/backend/runtime/giiant/fcd70a9bfdf8de75128d795dfc948a74
 *
 * @package default
 */


use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var \backend\models\ProfileForm $model
 */
$this->title = Yii::t('models', 'User');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'User'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->getUser()->id, 'url' => ['view', 'id' => $model->getUser()->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Edit');
?>
<div class="giiant-crud user-update">

    <h1>
        <?php echo Yii::t('models', 'User') ?>
        <small>
                        <?php echo Html::encode($model->getUser()->_label) ?>
        </small>
    </h1>

    <div class="crud-navigation">
        <?php echo Html::a('<span class="glyphicon glyphicon-file"></span> ' . Yii::t('app', 'View'), ['view', 'id' => $model->getUser()->id], ['class' => 'btn btn-default']) ?>
    </div>

    <hr />

    <?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
