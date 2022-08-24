<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\Office $model
*/

$this->title = Yii::t('app', 'Office');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Office'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="giiant-crud office-update">

    <h1>
        <?= Yii::t('app', 'Office') ?>
        <small>
                        <?= Html::encode($model->id) ?>
        </small>
    </h1>

    <div class="crud-navigation">
        <?= Html::a('<span class="glyphicon glyphicon-file"></span> ' . 'View', ['view', 'id' => $model->id], ['class' => 'btn btn-default']) ?>
    </div>

    <hr />

    <?php echo $this->render('_form', [
    'model' => $model,
    ]); ?>

</div>
