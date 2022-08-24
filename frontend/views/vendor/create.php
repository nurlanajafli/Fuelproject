<?php
/**
 * /var/www/html/frontend/runtime/giiant/fccccf4deb34aed738291a9c38e87215
 *
 * @package default
 */


use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var common\models\Vendor $model
 */
$this->title = Yii::t('app', 'Vendor');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Vendors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud vendor-create">

    <div class="crud-navigation float-right">
        <?php echo Html::a('<i class="fas fa fa-undo"></i> ' . 'Cancel', \yii\helpers\Url::previous(), ['class' => 'btn btn-sm btn-secondary']) ?>
    </div>

    <h1 class="h3 mb-4 text-gray-800">
        <?php echo Yii::t('app', 'Vendor') ?>
        <small><?php echo Html::encode($model->name) ?></small>
    </h1>

    <div class="card shadow mb-4">
        <?php echo $this->render("_toolbar", ['model' => $model, "modelName" => $modelName]) ?>
        <div class="card-body">
            <?php echo $this->render("_form", ["model" => $model]) ?>
        </div>
    </div>

</div>
