<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\VehicleServiceCode $model
*/

$this->title = Yii::t('app', 'Vehicle Service Code');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vehicle Service Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud vehicle-service-code-create">

    <h1>
        <?= Yii::t('app', 'Vehicle Service Code') ?>
        <small>
                        <?= Html::encode($model->code) ?>
        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?=             Html::a(
            'Cancel',
            \yii\helpers\Url::previous(),
            ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr />

    <?= $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
