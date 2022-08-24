<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\TruckType $model
*/

$this->title = Yii::t('app', 'Truck Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Truck Type'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->type, 'url' => ['view', 'type' => $model->type]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="giiant-crud truck-type-update">

    <h1>
        <?= Yii::t('app', 'Truck Type') ?>
        <small>
                        <?= Html::encode($model->type) ?>
        </small>
    </h1>

    <div class="crud-navigation">
        <?= Html::a('<span class="glyphicon glyphicon-file"></span> ' . 'View', ['view', 'type' => $model->type], ['class' => 'btn btn-default']) ?>
    </div>

    <hr />

    <?php echo $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
