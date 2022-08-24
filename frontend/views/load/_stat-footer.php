<?php

/**
* @var yii\web\View $this
* @var common\models\Load $model
*/
?>

<div class="row">
    <div class="col">
        <table class="table table--total table-sm table-borderless">
            <thead>
            <tr>
                <td><?=Yii::t('app', 'Booked')?></td>
                <td><?=Yii::t('app', 'Current')?></td>
                <td><?=Yii::t('app', 'Value')?></td>
                <td><?=Yii::t('app', 'Pieces')?></td>
                <td><?=Yii::t('app', 'Space')?></td>
                <td><?=Yii::t('app', 'Act Wgt')?></td>
                <td><?=Yii::t('app', 'Bil Wgt')?></td>
                <td><?=Yii::t('app', 'Rate By')?></td>
                <td><?=Yii::t('app', 'Rate')?></td>
                <td><?=Yii::t('app', 'Fit Rev')?></td>
                <td><?=Yii::t('app', 'Acc Rev')?></td>
                <td><?=Yii::t('app', 'Total Rev')?></td>
                <td><?=Yii::t('app', 'Discount')?></td>
                <td><?=Yii::t('app', 'Inv No')?></td>
                <td><?=Yii::t('app', 'Inv Date')?></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><?=$model->createdBy->username?></td>
                <td><?=$model->updatedBy->username?></td>
                <td></td>
                <td>0.00</td>
                <td>0.00</td>
                <td>0</td>
                <td>0</td>
                <td><?=$model->rate_by?></td>
                <td><?=$model->rate?></td>
                <td><?=$model->freight?></td>
                <td><?=$model->accessories?></td>
                <td><?=$model->total?></td>
                <td><?=$model->discount_amount?></td>
                <td></td>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>