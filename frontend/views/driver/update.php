<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\Driver $model
*/

$this->title = Yii::t('app', 'Driver');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Driver'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Edit');
?>

<div class="giiant-crud driver-update">

    <div class="crud-navigation float-right">
        <?= Html::a('<i class="fas fa fa-binoculars"></i> ' . Yii::t('app', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-sm btn-secondary']) ?>
        <?= Html::a('<i class="fas fa fa-list"></i> ' . Yii::t('app', 'Full list'), ['index'], ['class' => 'btn btn-sm btn-secondary']) ?>
    </div>

    <h1 class="h3 mb-4 text-gray-800">
        <?= Yii::t('app', 'Driver') ?>
        <small><?= Html::encode($model->id) ?></small>
    </h1>

    <div class="card shadow mb-4">
        <?= $this->render("_toolbar", ['model' => $model]) ?>
        <div class="card-body">
            <?= $this->render('_form', ['model' => $model, ]); ?>
        </div>
    </div>

</div>