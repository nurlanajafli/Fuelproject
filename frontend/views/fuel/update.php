<?php
use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var common\models\FuelImport $model
 */
$this->title = Yii::t('app', 'Fuel Update');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Fuel Import'), 'url' => ['import']];
$this->params['breadcrumbs'][] = Yii::t('app', 'Edit');
?>
<div class="modal fade" id="fuel_modal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white"></div>
            <div class="giiant-crud truck-update">
                <h1 class="h3 mt-2 ml-3 text-gray-800">
                    <?php echo Yii::t('app', 'Fuel') ?>
                    <small><?php echo Html::encode($model->id) ?></small>
                </h1>

                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span class="text-white">&times;</span>
                </button> 

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <?php echo $this->render("update-form", ["model" => $model]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
