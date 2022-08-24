<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\BusinessDirection $model
*/

$this->title = Yii::t('app', 'Business Direction');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Business Directions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud business-direction-create">

    <h1>
        <?= Yii::t('app', 'Business Direction') ?>
        <small>
                        <?= Html::encode($model->name) ?>
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
