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
 * @var common\models\Carrier $model
 * @var common\models\CarrierProfile $profileModel
 * @var common\models\LanePreference $lanePreferenceModel
 * @var common\models\Lane[] $laneModels
 */
$this->title = Yii::t('app', 'Carrier');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Carriers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud carrier-create">

    <div class="crud-navigation float-right">
        <?php echo Html::a('<i class="fas fa fa-undo"></i> ' . Yii::t('app', 'Cancel'), \yii\helpers\Url::previous(), ['class' => 'btn btn-sm btn-secondary']) ?>
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
