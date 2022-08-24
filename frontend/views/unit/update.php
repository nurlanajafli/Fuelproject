<?php
/**
 * /var/www/html/frontend/runtime/giiant/fcd70a9bfdf8de75128d795dfc948a74
 *
 * @package default
 *
 */

use yii\helpers\Html;


$this->title = Yii::t('app', 'Unit');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Units'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Edit');
?>
<div class="giiant-crud unit-update">
    <div class="crud-navigation float-right">
        <?php echo Html::a('<i class="fas fa fa-binoculars"></i> ' . Yii::t('app', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-sm btn-secondary']) ?>
        <?php echo Html::a('<i class="fas fa fa-list"></i> ' . Yii::t('app', 'Full list'), ['index'], ['class' => 'btn btn-sm btn-secondary']) ?>
    </div>
    <h1 class="h3 mb-4 text-gray-800">
        <?php echo Yii::t('app', 'Unit') ?>
        <small><?php echo Html::encode($model->id) ?></small>
    </h1>
    <div class="card shadow mb-4">
        <?php echo $this->render("_toolbar", ["model" => $model, "modelName" => "Unit"]) ?>
        <div class="card-body">
            <?php echo $this->render("_form", ["model" => $model, "allSelectedParts"=>$allSelectedParts, 'formConfig' => ['enableClientValidation' => true]]) ?>
        </div>
    </div>
</div>
