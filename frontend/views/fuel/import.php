<?php

/**
 * @var yii\web\View $this
 */

use yii\data\ActiveDataProvider;
use common\enums\FuelCardDataProvider as FC;
use common\widgets\DataTables\DataColumn;
use app\models\FuelImport;
use common\widgets\DataTables\ActionColumn;
use common\widgets\DataTables\Grid;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Fuel Card Data Import');
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?=$this->title?></h1>
    </div>

<?php $this->beginBlock('toolbar') ?>
    <div class="edit-form-toolbar">
        <?= Html::beginForm(null, null, ['class' => 'form-inline border-right']); ?>
        <?= Html::label(Yii::t('app', 'Provider'), "select-provider")?>
        <?= Html::dropDownList('provider', null, FC::getEnums(), ['id' => 'select-provider', 'class' => 'form-control mx-sm-3'])?>
        <?= Html::endForm(); ?>

        <button class="btn btn-link custom-ajax-modal" data-tooltip="tooltip" data-placement="top" data-url="<?= Url::toRoute(['fuel/ajax-import-form'])?>" title="<?=Yii::t('app', 'Import')?>"><i class="fas fa-download"></i></button>
        <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?=Yii::t('app', 'New')?>" disabled><i class="fas fa-plus-circle"></i></button>

        <div id="provider_options" class="mr-0">

            <!-- EFS -->
            <button class="btn btn-link js-ajax-modal provider provider-<?=FC::getKey(FC::EFS)?>" data-url="<?= Url::toRoute(['ajax-setup-product-codes', 'provider' => FC::EFS])?>" data-tooltip="tooltip" data-placement="top" title="<?=Yii::t('app', 'Setup Product Codes')?>"><i class="fas fa-cog"></i></button>
            <!-- TCH -->
            <div style="display: none" class="dropdown provider provider-<?=FC::getKey(FC::TCH)?>">
                <button class="btn btn-link dropdown-toggle" data-tooltip="tooltip" data-toggle="dropdown" data-placement="top" title="<?=Yii::t('app', 'Setup')?>">
                    <i class="fas fa-cog"></i><!--<i class="fas fa-caret-down"></i>-->
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item js-ajax-modal" data-url="<?= Url::toRoute(['ajax-setup-account', 'provider' => FC::TCH])?>" href="#"><?=Yii::t('app', 'Setup Account')?></a>
                    <a class="dropdown-item js-ajax-modal" data-url="<?= Url::toRoute(['ajax-setup-product-codes', 'provider' => FC::TCH])?>" href="#"><?=Yii::t('app', 'Setup Product Codes')?></a>
                </div>
            </div>
            <!-- KNOX -->
            <button style="display: none" class="btn btn-link js-ajax-modal provider provider-<?=FC::getKey(FC::KNOX)?>" data-url="<?= Url::toRoute(['ajax-setup-product-codes', 'provider' => FC::KNOX])?>" data-tooltip="tooltip" data-placement="top" title="<?=Yii::t('app', 'Setup Product Codes')?>"><i class="fas fa-cog"></i></button>
            <!-- T_CHEK -->
            <button style="display: none" class="btn btn-link js-ajax-modal provider provider-<?=FC::getKey(FC::T_CHEK)?>" data-url="<?= Url::toRoute(['ajax-setup-product-codes', 'provider' => FC::T_CHEK])?>" data-tooltip="tooltip" data-placement="top" title="<?=Yii::t('app', 'Setup Product Codes')?>"><i class="fas fa-cog"></i></button>
            <!-- COMDATA -->
            <div style="display: none" class="dropdown provider provider-<?=FC::getKey(FC::COMDATA)?>">
                <button class="btn btn-link dropdown-toggle" data-tooltip="tooltip" data-toggle="dropdown" data-placement="top" title="<?=Yii::t('app', 'Setup')?>">
                    <i class="fas fa-cog"></i><!--<i class="fas fa-caret-down"></i>-->
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item js-ajax-modal" data-url="<?= Url::toRoute(['ajax-setup-account', 'provider' => FC::COMDATA])?>"  href="#"><?=Yii::t('app', 'Setup Account')?></a>
                    <a class="dropdown-item js-ajax-modal" data-url="<?= Url::toRoute(['ajax-setup-product-codes', 'provider' => FC::COMDATA])?>" href="#"><?=Yii::t('app', 'Setup Product Codes')?></a>
                </div>
            </div>
            <!-- COMDATA_MC -->
            <div style="display: none" class="dropdown provider provider-<?=FC::getKey(FC::COMDATA_MC)?>">
                <button class="btn btn-link dropdown-toggle" data-tooltip="tooltip" data-toggle="dropdown" data-placement="top" title="<?=Yii::t('app', 'Setup')?>">
                    <i class="fas fa-cog"></i><!--<i class="fas fa-caret-down"></i>-->
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item js-ajax-modal" data-url="<?= Url::toRoute(['ajax-setup-account', 'provider' => FC::COMDATA_MC])?>"  href="#"><?=Yii::t('app', 'Setup Account')?></a>
                    <a class="dropdown-item js-ajax-modal" data-url="<?= Url::toRoute(['ajax-setup-product-codes', 'provider' => FC::COMDATA_MC])?>" href="#"><?=Yii::t('app', 'Setup Product Codes')?></a>
                </div>
            </div>
            <!-- MULTI_SERVICE -->
            <button style="display: none" class="btn btn-link js-ajax-modal provider provider-<?=FC::getKey(FC::MULTI_SERVICE)?>" data-url="<?= Url::toRoute(['ajax-setup-product-codes', 'provider' => FC::MULTI_SERVICE])?>" data-tooltip="tooltip" data-placement="top" title="<?=Yii::t('app', 'Setup Product Codes')?>"><i class="fas fa-cog"></i></button>
            <!-- FLEET_ONE -->
            <button style="display: none" class="btn btn-link js-ajax-modal provider provider-<?=FC::getKey(FC::FLEET_ONE)?>" data-url="<?= Url::toRoute(['ajax-setup-product-codes', 'provider' => FC::FLEET_ONE])?>" data-tooltip="tooltip" data-placement="top" title="<?=Yii::t('app', 'Setup Product Codes')?>"><i class="fas fa-cog"></i></button>
            <!-- TCH_CHECK -->
            <div style="display: none" class="dropdown provider provider-<?=FC::getKey(FC::TCH_CHECK)?>">
                <button class="btn btn-link dropdown-toggle" data-tooltip="tooltip" data-toggle="dropdown" data-placement="top" title="<?=Yii::t('app', 'Setup')?>">
                    <i class="fas fa-cog"></i><!--<i class="fas fa-caret-down"></i>-->
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item js-ajax-modal" data-url="<?= Url::toRoute(['ajax-setup-account', 'provider' => FC::TCH_CHECK])?>"  href="#"><?=Yii::t('app', 'Setup Account')?></a>
                    <a class="dropdown-item js-ajax-modal" data-url="<?= Url::toRoute(['ajax-setup-product-codes', 'provider' => FC::TCH_CHECK])?>" href="#"><?=Yii::t('app', 'Setup Product Codes')?></a>
                </div>
            </div>
            <!-- COM_CHEK -->
            <button style="display: none" class="btn btn-link js-ajax-modal provider provider-<?=FC::getKey(FC::COM_CHEK)?>" data-url="<?= Url::toRoute(['ajax-setup-product-codes', 'provider' => FC::COM_CHEK])?>" data-tooltip="tooltip" data-placement="top" title="<?=Yii::t('app', 'Setup Product Codes')?>"><i class="fas fa-cog"></i></button>
            <!-- TOLLS -->
            <button style="display: none" class="btn btn-link js-ajax-modal provider provider-<?=FC::getKey(FC::TOLLS)?>" data-url="<?= Url::toRoute(['ajax-setup-product-codes', 'provider' => FC::TOLLS])?>" data-tooltip="tooltip" data-placement="top" title="<?=Yii::t('app', 'Setup Product Codes')?>"><i class="fas fa-cog"></i></button>
            <!-- TOLLS2 -->
            <button style="display: none" class="btn btn-link js-ajax-modal provider provider-<?=FC::getKey(FC::TOLLS)?>2" data-url="<?= Url::toRoute(['ajax-setup-product-codes', 'provider' => FC::TOLLS2])?>" data-tooltip="tooltip" data-placement="top" title="<?=Yii::t('app', 'Setup Product Codes')?>"><i class="fas fa-cog"></i></button>
        </div>

        <button class="btn btn-link" data-url="<?=Url::toRoute(['ajax-setup-account', 'provider' => FC::COMDATA])?>" data-tooltip="tooltip" data-placement="top" title="<?=Yii::t('app', 'Post')?>"><i class="fas fa-save"></i></button>
        <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?=Yii::t('app', 'Research')?>" disabled><i class="fas fa-microscope"></i></button>
    </div>
<?php $this->endBlock(); ?>

<?php
$cardCheckNo = Yii::t('app', 'Card/Check&nbsp;No');
$driverName = Yii::t('app', 'Driver&nbsp;Name');
$tripNo = Yii::t('app', 'Trip&nbsp;No');
?>
<?= Grid::widget([
    'id' => 'fuel',
    'stateSave' => true,
    'rowAttributes' => 15,
    'colVis' => false,
    'toolbarHtml' => $this->blocks['toolbar'],
    'searching' => '',
    'dataProvider' => new ActiveDataProvider([
        'query' => FuelImport::find(),
        'pagination' => false
    ]),
    'attributes' => [
        'data-edit' => Url::toRoute(['update', 'id' => '{id}']),
        'data-delete' => Url::toRoute(['delete', 'id' => '{id}']),
    ],
    'columns' => [
        new DataColumn([
            'title' => '-',
            'value' => function (FuelImport $model) {
                return $model->id;
            },
            'className' => 'hidden'
        ]),
        "card_check_no|title=$cardCheckNo",
        'purchase_date',
        'purchase_time',
        "trip_no|title=$tripNo",
        new DataColumn([
            'title' => Yii::t('app', $driverName),
            'value' => function (FuelImport $model) {
                return $model->driver ? $model->driver->getFullName() : '';
            }
        ]),
        'truck',
        'trailer',
        'city',
        'state_id',
        'description',
        'cost',
        //'err',
        /*new ActionColumn([
            'html' => function ($model) {
                return Html::a('<i class="fas fa-edit"></i>', ['update', 'id' => $model->id], ['class' => 'px-1', 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Edit')])
                    . Html::a('<i class="fas fa-trash"></i>', ['delete', 'id' => $model->id], ['class' => 'px-1', 'data-method' => 'post', 'data-confirm' => Yii::t('app', 'Are you sure to delete this item?'), 'data-tooltip' => 'tooltip', 'data-placement' => 'top', 'title' => Yii::t('app', 'Delete')]);
            }
        ]),*/
    ],
]);

$js = <<<JSCODE
$('#select-provider').on('change', function (evt) {
    var providerName = $(evt.target).val();
    $("#provider_options .provider").hide();
    $("#provider_options .provider-" + providerName).show();
});
$(document).on(_touchTap, '.custom-ajax-modal', function (e) {
    e.preventDefault();    
    openModal($(this).data("url") + "?provider=" + $("#select-provider").val());
});

JSCODE;
$this->registerJs($js);