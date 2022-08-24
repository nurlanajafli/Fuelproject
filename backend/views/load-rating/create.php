<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var common\models\LoadRatingMatrix $model
 */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Load Rating Matrix'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Load Rating Matrix');
?>
<div class="giiant-crud">

    <h1>
        <?= Yii::t('app', 'Load Rating Matrix') ?>
        <small><?= Html::encode($model->number) ?></small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?=Html::a('Cancel', ['load-rating/index'], ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr />

    <?= $this->render('_form', ['model' => $model]); ?>

</div>
