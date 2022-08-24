<?php
/**
 * /var/www/html/frontend/runtime/giiant/fcd70a9bfdf8de75128d795dfc948a74
 *
 * @package default
 */


use yii\helpers\Html;
use common\enums\I18nCategory;

/**
 *
 * @var yii\web\View $this
 * @var common\models\DriverCompliance $model
 */
$this->title = Yii::t('app', 'Driver Compliance');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Driver Compliances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Edit');
?>
<div class="giiant-crud driver-compliance-update">

    <div class="crud-navigation float-right">
        <?php echo Html::a('<i class="fas fa fa-list"></i> ' . Yii::t('app', 'Full list'), ['index'], ['class' => 'btn btn-sm btn-secondary']) ?>
    </div>

    <h1 class="h3 mb-4 text-gray-800">
        <?php echo Yii::t('app', 'Driver Compliance') ?>
        <small><?php echo " - " . Html::encode($model->driver->getFullName()) ?></small>
    </h1>

    <div class="card shadow mb-4">
        <?php echo $this->render("_toolbar", ["modelName" => "Driver Compliance"]) ?>
        <div class="card-body">
            <?php echo $this->render("_form", ["model" => $model]) ?>
        </div>
    </div>

</div>
