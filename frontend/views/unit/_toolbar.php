<?php
/**
 * /var/www/html/frontend/runtime/giiant/f7eb1babf3c279e06df20ae63d4bed57
 *
 * @package default
 *
 */

use yii\helpers\Url;

?>
<div class="card-header py-3">
    <div class="edit-form-toolbar">
        <button disabled class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Images') ?>"><i class="fas fa-image"></i></button>
        <?php if ($model->id) { ?>
        <button class="btn btn-link js-ajax-modal" data-url="<?= Url::to(['set-location', 'id' => $model->id]) ?>" data-tooltip="tooltip" data-placement="top" title="<?= Yii::t('app', 'Modify location') ?>"><i class="fas fa-location-arrow"></i></button>
        <?php } ?>
        <button disabled class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Balances') ?>"><i class="fas fa-check"></i></button>
        <button disabled class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Reccuring Time Off') ?>"><i class="fas fa-calendar-alt"></i></button>
        <button disabled class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Taxes and Adjustments') ?>"><i class="fas fa-calculator"></i></button>
        <button disabled class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Register ' . $modelName . ' for Mobile App') ?>"><i class="fas fa-mobile"></i></button>
        <button disabled class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Terminate ' . strtolower($modelName)) ?>"><i class="fas fa-eraser"></i></button>
        <!--
        <div class="dropdown">
            <button class="btn btn-link dropdowwn-toggle" data-tooltip="tooltip" data-toggle="dropdown" data-placement="top" title="Link">
                <i class="fas fa-link"></i>
                <i class="fas fa-caret-down"></i>
            </button>
            <div class="dropdown-menu"><a class="dropdown-item" href="#">First</a><a class="dropdown-item" href="#">Second</a><a class="dropdown-item" href="#">Third</a></div>
        </div>
        -->
    </div>
</div>
