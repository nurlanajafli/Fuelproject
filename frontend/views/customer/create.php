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
 * @var common\models\Customer $model
 */
$this->title = Yii::t('app', 'Customer');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud customer-create">
    <div class="crud-navigation float-right">
        <?php echo Html::a('<i class="fas fa fa-undo"></i> ' . Yii::t('app', 'Cancel'), \yii\helpers\Url::previous(), ['class' => 'btn btn-sm btn-secondary']) ?>
    </div>
    <h1 class="h3 mb-4 text-gray-800">
        <?= Yii::t('app', 'Customer') ?>
        <small><?= Html::encode($model->name) ?></small>
    </h1>
    <div class="card shadow mb-4">
        <?= $this->render("_toolbar", ['model' => $model]) ?>
        <div class="card-body">
            <?= $this->render("_form", ['model' => $model]) ?>
        </div>
    </div>
</div>
