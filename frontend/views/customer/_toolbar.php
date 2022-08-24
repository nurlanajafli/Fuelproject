<?php
/**
 * /var/www/html/frontend/runtime/giiant/f7eb1babf3c279e06df20ae63d4bed57
 *
 * @package default
 */

/**
 * @var yii\web\View $this
 * @var common\models\Customer $model
 */


use yii\helpers\Url;
?>
<div class="card-header py-3">
    <div class="edit-form-toolbar">
        <button class="btn btn-link<?php if (!$model->id) : ?> disabled<?php endif ?>" data-toggle="modal" data-target="#customer-notes-modal" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Notes') ?>"><i class="fas fa-edit"></i></button>
        <button class="btn btn-link<?php if (!$model->id) : ?> disabled<?php else: ?> js-ajax-modal<?php endif ?>" data-url="<?php echo Url::toRoute(['document/index', 'type' => 'customer', 'id' => $model->id]) ?>" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Images') ?>">
            <i class="fas fa-image"></i>
        </button>
        <button disabled class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Credit') ?>"><i class="fas fa-check"></i></button>
        <button disabled class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Fax Documents') ?>"><i class="fas fa-fax"></i></button>
        <button disabled class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Post to Clipboard') ?>"><i class="fas fa-clipboard"></i></button>
        <button disabled class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Setup Charges') ?>"><i class="fas fa-calculator"></i></button>
        <button disabled class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Messaging Preferences') ?>"><i class="fas fa-link"></i></button>
        <button disabled class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Make Customer Inactive') ?>"><i class="fas fa-eraser"></i></button>
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
