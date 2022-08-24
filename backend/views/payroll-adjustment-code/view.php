<?php
/**
 * /var/www/html/frontend/runtime/giiant/d4b4964a63cc95065fa0ae19074007ee
 *
 * @package default
 */


use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;
use dmstr\bootstrap\Tabs;

/**
 *
 * @var yii\web\View $this
 * @var common\models\PayrollAdjustmentCode $model
 */
$copyParams = $model->attributes;

$this->title = Yii::t('app', 'Payroll Adjustment Code');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Payroll Adjustment Codes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->code, 'url' => ['view', 'code' => $model->code]];
$this->params['breadcrumbs'][] = 'View';
?>
<div class="giiant-crud payroll-adjustment-code-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?php echo \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?php echo Yii::t('app', 'Payroll Adjustment Code') ?>
        <small>
            <?php echo Html::encode($model->code) ?>
        </small>
    </h1>


    <div class="clearfix crud-navigation">

        <!-- menu buttons -->
        <div class='pull-left'>
            <?php echo Html::a(
	'<span class="glyphicon glyphicon-pencil"></span> ' . 'Edit',
	[ 'update', 'code' => $model->code],
	['class' => 'btn btn-info']) ?>

            <?php echo Html::a(
	'<span class="glyphicon glyphicon-copy"></span> ' . 'Copy',
	['create', 'code' => $model->code, 'PayrollAdjustmentCode'=>$copyParams],
	['class' => 'btn btn-success']) ?>

            <?php echo Html::a(
	'<span class="glyphicon glyphicon-plus"></span> ' . 'New',
	['create'],
	['class' => 'btn btn-success']) ?>
        </div>

        <div class="pull-right">
            <?php echo Html::a('<span class="glyphicon glyphicon-list"></span> '
	. 'Full list', ['index'], ['class'=>'btn btn-default']) ?>
        </div>

    </div>

    <hr/>

    <?php $this->beginBlock('\common\models\PayrollAdjustmentCode'); ?>


    <?php echo DetailView::widget([
		'model' => $model,
		'attributes' => [
			'code',
			'adj_type',
			'account',
			'post_to_carrier_id',
			'post_to_driver_id',
			'post_to_vendor_id',
			'percent',
			'amount',
			'empr_paid:boolean',
			'inactive:boolean',
			'adj_class',
			'based_on',
		],
	]); ?>


    <hr/>

    <?php echo Html::a('<span class="glyphicon glyphicon-trash"></span> ' . 'Delete', ['delete', 'code' => $model->code],
	[
		'class' => 'btn btn-danger',
		'data-confirm' => '' . 'Are you sure to delete this item?' . '',
		'data-method' => 'post',
	]); ?>
    <?php $this->endBlock(); ?>



<?php $this->beginBlock('Account0'); ?>
<div style='position: relative'>
<div style='position:absolute; right: 0px; top: 0px;'>
  <?php echo Html::a(
	'<span class="glyphicon glyphicon-list"></span> ' . 'List All' . ' Account0',
	['account/index'],
	['class'=>'btn text-muted btn-xs']
) ?>
  <?php echo Html::a(
	'<span class="glyphicon glyphicon-plus"></span> ' . 'New' . ' Account0',
	['account/create', 'Account' => ['account' => $model->code]],
	['class'=>'btn btn-success btn-xs']
); ?>
</div>
</div>
<?php $this->endBlock() ?>


    <?php echo Tabs::widget(
	[
		'id' => 'relation-tabs',
		'encodeLabels' => false,
		'items' => [
			[
				'label'   => '<b class=""># '.Html::encode($model->code).'</b>',
				'content' => $this->blocks['\common\models\PayrollAdjustmentCode'],
				'active'  => true,
			],
			[
				'content' => $this->blocks['Account0'],
				'label'   => '<small>Account0 <span class="badge badge-default">'. $model->getAccount0()->count() . '</span></small>',
				'active'  => false,
			],
		]
	]
);
?>
</div>
