<?php
/**
 * /var/www/html/frontend/runtime/giiant/f7eb1babf3c279e06df20ae63d4bed57
 *
 * @package default
 */


/**
 *
 * @var yii\web\View $this
 * @var \common\models\Trailer $model
 * @var string $modelName
 */

use yii\helpers\Url;
?>
<div class="card-header py-3">
    <div class="edit-form-toolbar">
        <button class="btn btn-link<?php if (!$model->id) : ?> disabled<?php else: ?> js-ajax-modal<?php endif ?>" data-url="<?php echo Url::toRoute(['document/index', 'type' => 'trailer', 'id' => $model->id]) ?>" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Images') ?>">
            <i class="fas fa-image"></i>
        </button>
        <button class="btn btn-link<?php if (!$model->id) : ?> disabled<?php else: ?> js-ajax-modal<?php endif ?>" data-url="<?= Url::to(['set-location', 'id' => $model->id]) ?>" data-tooltip="tooltip" data-placement="top" title="<?= Yii::t('app', 'Modify location') ?>"><i class="fas fa-location-arrow"></i></button>
        <button class="btn btn-link disabled" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Mark as Down') ?>"><i class="fas fa-suitcase-rolling"></i></button>
        <button class="btn btn-link disabled" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Out of Service') ?>"><i class="fas fa-eraser"></i></button>
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
