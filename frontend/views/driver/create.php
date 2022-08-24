<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\Driver $model
*/

$this->title = Yii::t('app', 'Driver');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Drivers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="giiant-crud driver-create">

    <div class="crud-navigation float-right">
        <?= Html::a('<i class="fas fa fa-undo"></i> ' . Yii::t('app', 'Cancel'), \yii\helpers\Url::previous(), ['class' => 'btn btn-sm btn-secondary']) ?>
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