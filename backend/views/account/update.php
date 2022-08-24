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
 * @var common\models\Account $model
 */
$this->title = Yii::t('app', 'Account');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Account'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->account, 'url' => ['view', 'account' => $model->account]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="giiant-crud account-update">

    <h1>
        <?php echo Yii::t('app', 'Account') ?>
        <small>
                        <?php echo Html::encode($model->account) ?>
        </small>
    </h1>

    <div class="crud-navigation">
        <?php echo Html::a('<span class="glyphicon glyphicon-file"></span> ' . 'View', ['view', 'account' => $model->account], ['class' => 'btn btn-default']) ?>
    </div>

    <hr />

    <?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
