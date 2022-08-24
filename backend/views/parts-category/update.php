<?php

use yii\helpers\Html;

/**
* @var yii\web\View $this
* @var common\models\PartsCategory $model
*/

$this->title = Yii::t('app', 'Parts Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parts Category'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->code, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'Edit';
?>
<div class="giiant-crud parts-category-update">

    <h1>
        <?= Yii::t('app', 'Parts Category') ?>
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
