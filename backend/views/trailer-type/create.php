<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\TrailerType $model
*/

$this->title = Yii::t('app', 'Trailer Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trailer Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud trailer-type-create">

    <h1>
        <?= Yii::t('app', 'Trailer Type') ?>
        <small>
                        <?= Html::encode($model->type) ?>
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
