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
 * @var common\models\Commodity $model
 */
$this->title = Yii::t('models', 'Commodity');
$this->params['breadcrumbs'][] = ['label' => Yii::t('models', 'Commodities'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="giiant-crud commodity-create">

    <h1>
        <?php echo Yii::t('models', 'Commodity') ?>
        <small>
                        <?php echo Html::encode($model->id) ?>
        </small>
    </h1>

    <div class="clearfix crud-navigation">
        <div class="pull-left">
            <?php echo             Html::a(
	Yii::t('app', 'Cancel'),
	\yii\helpers\Url::previous(),
	['class' => 'btn btn-default']) ?>
        </div>
    </div>

    <hr />

    <?php echo $this->render('_form', [
		'model' => $model,
	]); ?>

</div>
