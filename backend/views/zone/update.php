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
 * @var common\models\Zone $model
 */
$this->title = Yii::t('app', 'Zone');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Zone'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->code, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="giiant-crud zone-update">

    <h1>
        <?php echo Yii::t('app', 'Zone') ?>
        <small>
                        <?php echo Html::encode($model->code) ?>
        </small>
    </h1>

    <div class="crud-navigation">
        <?php echo Html::a('<span class="glyphicon glyphicon-file"></span> ' . 'View', ['view', 'code' => $model->code], ['class' => 'btn btn-default']) ?>
    </div>

    <hr />

    <?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
