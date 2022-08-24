<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\AccidentCode $model
*/

$this->title = Yii::t('app', 'Accident Code');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Accident Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud accident-code-create">

    <h1>
        <?= Yii::t('app', 'Accident Code') ?>
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
