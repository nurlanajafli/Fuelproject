<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\LogActivityCode $model
*/

$this->title = Yii::t('app', 'Log Activity Code');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Log Activity Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud log-activity-code-create">

    <h1>
        <?= Yii::t('app', 'Log Activity Code') ?>
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
