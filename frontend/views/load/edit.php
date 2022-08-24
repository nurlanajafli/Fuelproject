<?php

use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var common\models\Load $model
 */
$this->title = Yii::t('app', 'Load');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Loads'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud load-edit">

    <div class="crud-navigation float-right">
        <?php echo Html::a('<i class="fas fa fa-undo"></i> ' . Yii::t('app', 'Cancel'), \yii\helpers\Url::previous(), ['class' => 'btn btn-sm btn-secondary']) ?>
    </div>

    <h1 class="h3 mb-4 text-gray-800">
        <?php echo Yii::t('app', 'Load') ?>
        <small><?=$model->id ?></small> TL
    </h1>

    <div class="card shadow mb-4">
        <?php echo $this->render("_toolbar", ['model' => $model]) ?>
        <div class="card-body">
            <?php echo $this->render("_form", ["model" => $model]) ?>
        </div>
    </div>

</div>
