<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\AccidentCode $model
*/

$this->title = Yii::t('app', 'Accident Code');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Accident Code'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->code, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="giiant-crud accident-code-update">

    <h1>
        <?= Yii::t('app', 'Accident Code') ?>
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
