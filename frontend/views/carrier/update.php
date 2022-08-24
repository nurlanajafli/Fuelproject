<?php
/**
 * /var/www/html/frontend/runtime/giiant/fcd70a9bfdf8de75128d795dfc948a74
 *
 * @package default
 */


use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var common\models\Carrier $model
 * @var common\models\CarrierProfile $profileModel
 * @var common\models\LanePreference $lanePreferenceModel
 * @var common\models\Lane[] $laneModels
 */
$this->title = Yii::t('app', 'Carrier');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Carriers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Edit');
?>
<div class="giiant-crud carrier-update">

    <div class="crud-navigation float-right">
        <?php echo Html::a('<i class="fas fa fa-binoculars"></i> ' . Yii::t('app', 'View'), ['view', 'id' => $model->id], ['class' => 'btn btn-sm btn-secondary']) ?>
        <?php echo Html::a('<i class="fas fa fa-list"></i> ' . Yii::t('app', 'Full list'), ['index'], ['class' => 'btn btn-sm btn-secondary']) ?>
    </div>

    <h1 class="h3 mb-4 text-gray-800">
        <?php echo Yii::t('app', 'Carrier') ?>
        <small><?php echo Html::encode($model->name) ?></small>
    </h1>

    <div class="card shadow mb-4">
        <?php echo $this->render("_toolbar", ['model' => $model, "modelName" => 'Carrier']) ?>
        <div class="card-body">
            <?php echo $this->render("_form", [
                "model" => $model,
                "profileModel" => $profileModel,
                "lanePreferenceModel" => $lanePreferenceModel,
                "laneModels" => $laneModels,
            ]) ?>
        </div>
    </div>

</div>
