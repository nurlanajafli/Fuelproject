<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\ViolationMessage $model
*/

$this->title = Yii::t('app', 'Violation Message');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Violation Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud violation-message-create">

    <h1>
        <?= Yii::t('app', 'Violation Message') ?>
        <small>
                        <?= Html::encode($model->id) ?>
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
