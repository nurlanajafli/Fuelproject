<?php
/**
 * /var/www/html/frontend/runtime/giiant/fccccf4deb34aed738291a9c38e87215
 *
 * @package default
 *
 */

use yii\helpers\Html;


$this->title = Yii::t('app', 'Unit');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Units'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud unit-create">

    <div class="crud-navigation float-right">
        <?php echo Html::a('<i class="fas fa fa-undo"></i> ' . Yii::t('app', 'Cancel'), \yii\helpers\Url::previous(), ['class' => 'btn btn-sm btn-secondary']) ?>
    </div>

    <h1 class="h3 mb-4 text-gray-800">
        <?php echo Yii::t('app', 'Unit') ?>
        <small><?php echo Html::encode($model->id) ?></small>
    </h1>

    <div class="card shadow mb-4">
        <?php echo $this->render("_toolbar", ["model" => $model, "modelName" => "Unit"]) ?>
        <div class="card-body">
            <?php echo $this->render("_form", ["model" => $model, "allSelectedParts"=>$allSelectedParts, 'formConfig' => $formConfig]) ?>
        </div>
    </div>

</div>
