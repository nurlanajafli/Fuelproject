<?php


use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var common\models\Load $model
 */
?>

<div class="form-fieldset">
    <table class="table table-bordered table-sm">
        <thead>
        <tr>
            <th scope="col"><?=Yii::t('app', 'Accessorial')?></th>
            <th scope="col"><?=Yii::t('app', 'Ref')?></th>
            <th scope="col"><?=Yii::t('app', 'Rate')?></th>
            <th scope="col"><?=Yii::t('app', 'Units')?></th>
            <th scope="col"><?=Yii::t('app', 'Amt')?></th>
            <th scope="col"><?=Yii::t('app', 'Pay')?></th>
            <th scope="col"><?=Yii::t('app', 'App')?></th>
            <th scope="col"><?=Yii::t('app', 'Notes')?></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td scope="row" colspan="16">
                <button type="button" class="btn btn-sm btn-outline-primary js-ajax-modal" data-url="<?= Url::toRoute(['load/edit-accessory', 'load' => $model->id]) ?>" data-tooltip="tooltip" data-placement="top" title="<?=Yii::t('app', 'Add accessory')?>"><?=Yii::t('app', 'Add')?></button>
            </td>
        </tr>
        <?php foreach ($model->loadAccessories as $accessory): ?>
        <?php /** @var \common\models\LoadAccessory $accessory */ ?>
            <tr>
                <td><?=$accessory->accessorial->accessorialRating->description;?></td>
                <td><?=$accessory->reference;?></td>
                <td><?=$accessory->rate_each;?></td>
                <td><?=$accessory->units;?></td>
                <td><?=$accessory->amount;?></td>
                <td><?=Yii::$app->formatter->asBoolean($accessory->accessorial->accessorialRating->pay_driver);?></td>
                <td></td>
                <td><?=$accessory->notes;?></td>
                <td>
                    <?=Html::a('<i class="fa fa-edit"></i>', "#", [
                        'class' => "acc-edit js-ajax-modal",
                        'data-url' => Url::to(['load/edit-accessory', 'load' => $model->id, 'id' => $accessory->id])
                    ]);?>
                    <?=Html::a('<i class="fa fa-trash"></i>', Url::to(['load/delete-accessory', 'load' => $model->id, 'id' => $accessory->id]), [
                        'data-confirm' => Yii::t('app', 'Are you sure to delete this item?')
                    ]);?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
