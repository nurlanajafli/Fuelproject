<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\VehicleServiceCode $model
*/

$this->title = Yii::t('app', 'Vehicle Service Code');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vehicle Service Code'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->code, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="giiant-crud vehicle-service-code-update">

    <h1>
        <?= Yii::t('app', 'Vehicle Service Code') ?>
        <small>
                        <?= Html::encode($model->code) ?>
        </small>
    </h1>

    <div class="crud-navigation">
        <?= Html::a('<span class="glyphicon glyphicon-file"></span> ' . 'View', ['view', 'code' => $model->code], ['class' => 'btn btn-default']) ?>
    </div>

    <hr />

    <?php echo $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
