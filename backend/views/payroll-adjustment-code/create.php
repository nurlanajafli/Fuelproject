<?php
/**
 * /var/www/html/frontend/runtime/giiant/fccccf4deb34aed738291a9c38e87215
 *
 * @package default
 */


use common\enums\PayrollAdjustmentType;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 *
 * @var yii\web\View $this
 * @var common\models\PayrollAdjustmentCode $model
 */
$this->title = Yii::t('app', 'Payroll Adjustment Code');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payroll Adjustment Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud payroll-adjustment-code-create">

    <h1>
        <?php echo Yii::t('app', 'Payroll Adjustment Code') ?>
        <small><?php echo Html::encode($model->code) ?></small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?=Html::a(Yii::t('app', 'Cancel'), Url::previous(), ['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr />

    <?php $model->adj_type = PayrollAdjustmentType::LOCAL_INCOME_TAX ?>
    <?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
