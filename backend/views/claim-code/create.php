<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\ClaimCode $model
*/

$this->title = Yii::t('app', 'Claim Code');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Claim Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud claim-code-create">

    <h1>
        <?= Yii::t('app', 'Claim Code') ?>
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
