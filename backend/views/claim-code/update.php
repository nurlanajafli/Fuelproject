<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\ClaimCode $model
*/

$this->title = Yii::t('app', 'Claim Code');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Claim Code'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->code, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="giiant-crud claim-code-update">

    <h1>
        <?= Yii::t('app', 'Claim Code') ?>
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
