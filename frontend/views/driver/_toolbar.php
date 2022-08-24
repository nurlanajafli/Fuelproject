<?php
/**
 * @var \common\models\Driver $model
 */

use yii\helpers\Url;
use common\models\User;

$registered = $model->user_id && ($model->user->status == User::STATUS_ACTIVE);
?>
<div class="card-header py-3">
    <div class="edit-form-toolbar">
        <button class="btn btn-link<?php if (!$model->id) : ?> disabled<?php else: ?> js-ajax-modal<?php endif ?>"
                data-url="<?php echo Url::toRoute(['document/index', 'type' => 'driver', 'id' => $model->id]) ?>"
                data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Images') ?>"
               >
            <i class="fas fa-image"></i>
        </button>
        <button class="btn btn-link disabled" data-tooltip="tooltip" data-placement="top"
                title="<?= Yii::t('app', 'Balances') ?>"><i class="fas fa-check"></i></button>
        <button class="btn btn-link disabled" data-tooltip="tooltip" data-placement="top"
                title="<?= Yii::t('app', 'Reccuring Time Off') ?>"><i class="fas fa-calendar-alt"></i></button>
        <button class="btn btn-link<?php if (!$model->id) : ?> disabled<?php else: ?> js-ajax-modal<?php endif ?>"
                data-url="<?= Url::toRoute(['tax', 'id' => $model->id]) ?>" data-tooltip="tooltip"
                data-placement="top" title="<?= Yii::t('app', 'Taxes and Adjustments') ?>">
            <i class="fas fa-calculator"></i>
        </button>
        <button class="btn btn-link<?php if (!$model->id) : ?> disabled<?php else: ?> js-ajax-modal<?php endif ?>"
                data-url="<?= Url::toRoute([($registered ? 'unregister' : 'register'), 'id' => $model->id]) ?>"
                data-tooltip="tooltip" data-placement="top"
                title="<?= Yii::t('app', ($registered ? 'Unregister' : 'Register') . ' Driver for Mobile App') ?>"><i
                    class="fas fa-mobile"></i></button>
        <button class="btn btn-link disabled" data-tooltip="tooltip" data-placement="top"
                title="<?= Yii::t('app', 'Terminate driver') ?>"><i class="fas fa-eraser"></i></button>
    </div>
</div>