<?php
/**
 * /var/www/html/backend/runtime/giiant/fccccf4deb34aed738291a9c38e87215
 *
 * @package default
 */


use yii\helpers\Html;

/**
 *
 * @var yii\web\View $this
 * @var common\models\PurchaseOrderCode $model
 */
$this->title = Yii::t('app', 'Purchase Order Code');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Purchase Order Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud purchase-order-code-create">

    <h1>
        <?php echo Yii::t('app', 'Purchase Order Code') ?>
        <small>
                        <?php echo Html::encode($model->code) ?>
        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?php echo             Html::a(
	'Cancel',
	\yii\helpers\Url::previous(),
	['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr />

    <?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
