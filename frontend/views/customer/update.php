<?php
/**
 * /var/www/html/frontend/runtime/giiant/fcd70a9bfdf8de75128d795dfc948a74
 *
 * @package default
 */


use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var common\models\Customer $model
 */
$this->title = Yii::t('app', 'Customer');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Edit');
?>
<div class="giiant-crud customer-update">
    <div class="crud-navigation float-right">
        <?php echo Html::a('<i class="fas fa fa-binoculars"></i> ' . Yii::t('app', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-sm btn-secondary']) ?>
        <?php echo Html::a('<i class="fas fa fa-list"></i> ' . Yii::t('app', 'Full list'), ['index'], ['class' => 'btn btn-sm btn-secondary']) ?>
    </div>
    <h1 class="h3 mb-4 text-gray-800">
        <?= Yii::t('app', 'Customer') ?>
        <small><?= Html::encode($model->name) ?></small>
    </h1>
    <div class="card shadow mb-4">
        <?= $this->render("_toolbar", ['model' => $model]) ?>
        <div class="card-body">
            <?= $this->render("_form", ['model' => $model]) ?>
        </div>
    </div>
</div>
