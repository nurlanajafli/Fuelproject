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
use yii\bootstrap4\Tabs;

/**
 *
 * @var yii\web\View $this
 * @var common\models\Customer $model
 */
$copyParams = $model->attributes;

$this->title = Yii::t('app', 'Customer');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'View');
?>
<div class="giiant-crud customer-view">

    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?php echo \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>

    <h1>
        <?php echo Yii::t('app', 'Customer') ?>
        <small>
            <?php echo Html::encode($model->name) ?>
        </small>
    </h1>


    <div class="clearfix crud-navigation">

        <!-- menu buttons -->
        <div class='float-left'>
            <?php echo Html::a(
                '<i class="fa fa-edit"></i> ' . Yii::t('app', 'Edit'),
                ['update', 'id' => $model->id],
                ['class' => 'btn btn-sm btn-secondary']) ?>

            <?php echo Html::a(
                '<i class="fa fa-copy"></i> ' . Yii::t('app', 'Copy'),
                ['create', 'id' => $model->id, 'Customer' => $copyParams],
                ['class' => 'btn btn-sm btn-secondary']) ?>

            <?php echo Html::a(
                '<i class="fa fa-plus"></i> ' . Yii::t('app', 'New'),
                ['create'],
                ['class' => 'btn btn-sm btn-secondary']) ?>
        </div>

        <div class="float-right">
            <?php echo Html::a('<i class="fa fa-list"></i> '
                . Yii::t('app', 'Full list'), ['index'], ['class' => 'btn btn-sm btn-secondary']) ?>
        </div>

    </div>

    <hr/>

    <?php $this->beginBlock('common\models\Customer'); ?>


    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'address_1',
            'address_2',
            'city',
            // generated by schmunk42\giiant\generators\crud\providers\core\RelationProvider::attributeFormat
            [
                'format' => 'html',
                'attribute' => 'state_id',
                'value' => ($model->state ?
                    Html::a('<i class="glyphicon glyphicon-list"></i>', ['state/index']) . ' ' .
                    Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> ' . $model->state->_label, ['state/view', 'id' => $model->state->id,]) . ' ' .
                    Html::a('<i class="glyphicon glyphicon-paperclip"></i>', ['create', 'Customer' => ['state_id' => $model->state_id]])
                    :
                    '<span class="label label-warning">?</span>'),
            ],
            'zip',
            'main_phone',
            'main_800',
            'main_fax',
            'disp_contact',
            'ap_contact',
            'other_contact',
            'account_no',
            'federal_id',
            'mc_id',
            'scac',
            'notes:ntext',
        ],
    ]); ?>

    <hr/>

    <?php echo Html::a('<span class="glyphicon glyphicon-trash"></span> ' . Yii::t('app', 'Delete'), ['delete', 'id' => $model->id],
        [
            'class' => 'btn btn-danger',
            'data-confirm' => '' . Yii::t('app', 'Are you sure to delete this item?') . '',
            'data-method' => 'post',
        ]); ?>
    <?php $this->endBlock(); ?>



    <?php echo Tabs::widget(
        [
            'id' => 'relation-tabs',
            'encodeLabels' => false,
            'items' => [
                [
                    'label' => '<b class=""># ' . Html::encode($model->id) . '</b>',
                    'content' => $this->blocks['common\models\Customer'],
                    'active' => true,
                ],
            ]
        ]
    );
    ?>
</div>