<?php
/**
 * /var/www/html/frontend/runtime/giiant/f7eb1babf3c279e06df20ae63d4bed57
 *
 * @package default
 */

use yii\helpers\Url;

/**
 *
 * @var yii\web\View $this
 * @var string $modelName
 * @var \common\models\Truck $model
 */

?>
<div class="card-header py-3">
    <div class="edit-form-toolbar">
        <button class="btn btn-link<?php if (!$model->id) : ?> disabled<?php else: ?> js-ajax-modal<?php endif ?>" data-url="<?php echo Url::toRoute(['document/index', 'type' => 'truck', 'id' => $model->id]) ?>" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Images') ?>">
            <i class="fas fa-image"></i>
        </button>
        <button class="btn btn-link<?php if (!$model->id) : ?> disabled<?php else: ?> js-ajax-modal<?php endif ?>" data-url="<?= Url::toRoute(['list-odom', 'id' => $model->id]) ?>" data-tooltip="tooltip" data-placement="top" title="<?= Yii::t('app', 'Odometer readings') ?>"><i class="fas fa-receipt"></i></button>
        <button class="btn btn-link<?php if (!$model->id) : ?> disabled<?php else: ?> js-ajax-modal<?php endif ?>" data-url="<?= Url::to(['set-location', 'id' => $model->id]) ?>" data-tooltip="tooltip" data-placement="top" title="<?= Yii::t('app', 'Modify location') ?>"><i class="fas fa-location-arrow"></i></button>
        <button class="btn btn-link<?php if (!$model->id) : ?> disabled<?php else: ?> js-ajax-modal<?php endif ?>" data-url="<?= Url::toRoute(['out-of-service', 'id' => $model->id]) ?>" data-tooltip="tooltip" data-placement="top" title="<?= Yii::t('app', 'Out of Service') ?>"><i class="fas fa-eraser"></i></button>
    </div>
</div>
