<?php

use common\helpers\Hosts;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap4\Tabs;
use yii\helpers\Url;

/**
 *
 * @var yii\web\View $this
 * @var common\models\Report $model
 */
$copyParams = $model->attributes;

$this->title = Yii::t('app', 'Report');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Report list'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => (string)$model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'View');
?>
<div class="giiant-crud trailer-view">
    <!-- flash message -->
    <?php if (\Yii::$app->session->getFlash('deleteError') !== null) : ?>
        <span class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
            <?php echo \Yii::$app->session->getFlash('deleteError') ?>
        </span>
    <?php endif; ?>
    <h1>
        <?php echo Yii::t('app', 'Report') ?>
        <small>
            <?php echo Html::encode($model->id) ?>
        </small>
    </h1>
    <div class="clearfix crud-navigation">
        <div class="float-right">
            <?php echo Html::a('<i class="fa fa-list"></i> '
                . Yii::t('app', 'Full list'), ['index'], ['class'=>'btn btn-sm btn-secondary']) ?>
        </div>
    </div>
    <hr/>
    <?php $this->beginBlock('common\models\Report'); ?>
    <?php echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            'type',
            'status',
            [
                'format' => 'html',
                'attribute' => 'driver',
                'value' => ($model->driver ?
                    Html::a('<i class="glyphicon glyphicon-list"></i>', ['driver/index']).' '.
                    Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->driver->_label, ['driver/view', 'id' => $model->driver->id, ],['target'=>'_blank','class'=>'link-primary','style'=>'color:#4e73df']) :
                    '<span class="label label-warning">?</span>'),
            ],
            [
                'format' => 'html',
                'attribute' => 'truck',
                'value' => ($model->truck ?
                    Html::a('<i class="glyphicon glyphicon-list"></i>', ['truck/index']).' '.
                    Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->truck->_label, ['truck/view', 'id' => $model->truck->id, ],['target'=>'_blank','class'=>'link-primary','style'=>'color:#4e73df']) :
                    '<span class="label label-warning">?</span>'),
            ],
            [
                'format' => 'html',
                'attribute' => 'trailer',
                'value' => ($model->trailer ?
                    Html::a('<i class="glyphicon glyphicon-list"></i>', ['trailer/index']).' '.
                    Html::a('<i class="glyphicon glyphicon-circle-arrow-right"></i> '.$model->trailer->_label, ['trailer/view', 'id' => $model->trailer->id, ],['target'=>'_blank','class'=>'link-primary','style'=>'color:#4e73df']) :
                    '<span class="label label-warning">?</span>'),
            ],
            'def_level',
            'fuel_level',
            'mileage',
            'created_at',
            [
                'format' => 'html',
                'attribute' => 'created_by',
                'value' => ($model->created_by ? $model->getCreatedBy()->one()->first_name.' '.$model->getCreatedBy()->one()->last_name
                    : '<span class="label label-warning">?</span>'),
            ],
            [
                'format' => 'html',
                'attribute' => 'signature_file',
                'value' => ($model->signature_file ?
                    '<img src="'.Url::to(Hosts::getImageCdn()."/".$model->signature_file.'" style="max-width:150px;max-height:150px;"><br>'.
                    Html::a('View full image', Url::to(Hosts::getImageCdn()."/".$model->signature_file), ['target'=>'_blank','class'=>'link-primary','style'=>'color:#4e73df'])) :
                    '<span class="label label-warning">?</span>'),
            ],
        ],
    ]); ?>
    <hr/>
    <?php $this->endBlock(); ?>
    <?php $this->beginBlock('ReportMedia'); ?>
    <br>
    <?php
    $convertToFormat = function($size, $precision = 2)
    {
        $base = log($size, 1024);
        $suffixes = array('', 'kB', 'mB', 'gB', 'tB');
        return round(pow(1024, $base - floor($base)), $precision) .''. $suffixes[floor($base)];
    };
    ?>
        <table class="table table-bordered table-striped w-100">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Side</th>
                    <th scope="col">Is Major</th>
                    <th scope="col">Is Interior</th>
                    <th scope="col">Description</th>
                    <th scope="col">Damage Type</th>
                    <th scope="col">Image</th>
                    <th scope="col">Created by<br>Updated by</th>
                    <th scope="col">Created at<br>Updated at</th>
                </tr>
            </thead>
            <tbody>
            <?php $i=1; foreach($model->reportMedia as $rm) { ?>
                <tr>
                    <td><?=$i?></td>
                    <td class="text-center"><b><?=$rm->side?></b></td>
                    <td><?=$rm->is_major ? 'Yes' : 'No';?></td>
                    <td><?=$rm->is_interior ? 'Yes': 'No';?></td>
                    <td><p><?=$rm->description?></p></td>
                    <td><?=$rm->damage_type?></td>
                    <td class="text-center">
                        <?php if($rm->file && $rm->file != ''): ?>
                            <img src="<?=Url::to(Hosts::getImageCdn()."/".$rm->file)?>" style="max-width:280px;max-height: 160px;display:block;margin:0 auto;"><br>
                            Size: <?=$convertToFormat($rm->size)?>&nbsp;
                            <?=Html::a('View full size', Url::to(Hosts::getImageCdn()."/".$rm->file), ['target'=>'_blank','class'=>'link-primary text-center','style'=>'color:#4e73df'])?>
                        <?php endif ?>
                    </td>
                    <td><?php
                        $createdBy = $rm->getCreatedBy()->one();
                        $updatedBy = $rm->getUpdatedBy()->one();
                        echo $createdBy->first_name." ".$createdBy->last_name."<br>(".$createdBy->email.")";
                        echo "<hr>";
                        echo $updatedBy->first_name." ".$updatedBy->last_name."<br>(".$updatedBy->email.")";
                        ?>
                    </td>
                    <td><?=Yii::$app->formatter->asDate($rm->created_at,Yii::$app->params['formats'][7])?><hr><?=Yii::$app->formatter->asDate($rm->updated_at,Yii::$app->params['formats'][7])?></td>
                </tr>
            <?php $i++; } ?>
            </tbody>
        </table>
    <?php $this->endBlock(); ?>
    <?php echo Tabs::widget(
        [
            'id' => 'relation-tabs',
            'encodeLabels' => false,
            'items' => [
                [
                    'label'   => '<b class=""># '.Html::encode($model->id).'</b>',
                    'content' => $this->blocks['common\models\Report'],
                    'active'  => true,
                ],
                [
                    'label'   => '<b class="">Report Media</b>',
                    'content' => $this->blocks['ReportMedia'],
                    'active'  => false,
                ],
            ]
        ]
    );
    ?>
</div>
