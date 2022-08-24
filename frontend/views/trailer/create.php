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
 * @var common\models\Trailer $model
 */
$this->title = Yii::t('app', 'Trailer');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Trailers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud trailer-create">

    <div class="crud-navigation float-right">
        <?php echo Html::a('<i class="fas fa fa-undo"></i> ' . Yii::t('app', 'Cancel'), \yii\helpers\Url::previous(), ['class' => 'btn btn-sm btn-secondary']) ?>
    </div>

    <h1 class="h3 mb-4 text-gray-800">
        <?php echo Yii::t('app', 'Trailer Information') ?>
    </h1>

    <div class="card shadow mb-4">
        <?php echo $this->render("_toolbar", ['model' => $model, "modelName" => $modelName]) ?>
        <div class="card-body">
            <?php echo $this->render("_form", ["model" => $model]) ?>
        </div>
    </div>

</div>
