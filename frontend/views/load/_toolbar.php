<?php

use common\enums\I18nCategory;
use common\enums\LoadStatus;
use common\models\Load;
use yii\helpers\Url;

/** @var Load $model */
?>
    <div class="card-header py-3">
        <div class="edit-form-toolbar">
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle" <?php if (!$model->bill_miles) echo "disabled"?> data-tooltip="tooltip" data-toggle="dropdown" data-placement="top" title="New"><i class="fas fa-plus"></i></button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?=Url::toRoute('load/edit')?>"><?=Yii::t('app', 'Book New Load')?></a>
                    <a class="dropdown-item js-ajax-modal" data-url="<?= Url::toRoute(['duplicate-load', 'load' => $model->id]) ?>" href="#"><?=Yii::t('app', 'Duplicate Load')?></a>
                    <a class="dropdown-item disabled" href="#">Bill Third Party</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle" disabled data-tooltip="tooltip" data-toggle="dropdown" data-placement="top" title="Quotes"><i class="fas fa-comment"></i></button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Import Quote</a>
                    <a class="dropdown-item" href="#">Save As Quote</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle" data-tooltip="tooltip" data-toggle="dropdown" data-placement="top" title="Change Status"><i class="fas fa-pen"></i></button>
                <div class="dropdown-menu">
                    <?php if ($model->status == LoadStatus::RESERVED) : ?>
                        <a class="dropdown-item" data-confirm="Load will be Reset. Area You Sure?" data-method="POST" href="<?=Url::toRoute(['load/reset', 'load' => $model->id])?>">Reset Load</a>
                    <?php endif; ?>
                    <?php if (!in_array($model->status, [LoadStatus::ENROUTED, LoadStatus::RESERVED])) : ?>
                        <a class="dropdown-item" data-confirm="Load will be Cancelled. Area You Sure?" data-method="POST" href="<?=Url::toRoute(['load/cancel', 'load' => $model->id])?>">Cancel Load</a>
                    <?php endif; ?>
                    <?php if (in_array($model->status, [LoadStatus::AVAILABLE, LoadStatus::PENDING, LoadStatus::POSSIBLE])) : ?>
                        <a class="dropdown-item js-ajax-modal" data-url="<?= Url::toRoute(['change-status', 'load' => $model->id]) ?>" href="#">Change Status</a>
                    <?php endif; ?>
                    <a class="dropdown-item disabled" href="#">Mark As Recurring</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle" disabled data-tooltip="tooltip" data-toggle="dropdown" data-placement="top" title="Find Functions"><i class="fas fa-search"></i></button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Find Unit</a>
                    <a class="dropdown-item" href="#">Find Carrier</a>
                </div>
            </div>
            <?php if ($model->status == LoadStatus::AVAILABLE || $model->status == LoadStatus::RESERVED) : ?>
                <button class="btn btn-link js-ajax-modal" data-url="<?= Url::toRoute(['dispatch-load', 'id' => $model->id]) ?>" data-tooltip="tooltip" data-placement="top" title="Dispatch Load"><i class="fas fa-truck-loading"></i></button>
            <?php endif; ?>
            <button class="btn btn-link js-ajax-modal" data-url="<?= Url::toRoute(['dispatch-summary', 'load' => $model->id]) ?>" data-tooltip="tooltip" data-placement="top" title="Dispatch Summary"><i class="fas fa-truck"></i></button>
            <button class="btn btn-link js-ajax-modal" data-url="<?= Url::toRoute(['rate', 'id' => $model->id]) ?>" data-tooltip="tooltip" data-placement="top" title="Rate"><i class="fas fa-calculator"></i></button>
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle" disabled data-tooltip="tooltip" data-toggle="dropdown" data-placement="top" title="Linked Info"><i class="fas fa-cloud"></i></button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="#">Post To Internet Load Board</a>
                    <a class="dropdown-item" href="#">Post To Internet Freight Tracking</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Setup Status Notification Rules</a>
                    <a class="dropdown-item" href="#">Load Pings</a>
                    <a class="dropdown-item" href="#">214 History</a>
                </div>
            </div>
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle" data-tooltip="tooltip" data-toggle="dropdown" data-placement="top" title="Print"><i class="fas fa-print"></i></button>
                <div class="dropdown-menu">
                    <a class="dropdown-item js-ajax-modal" data-url="<?= Url::toRoute(['loadsheet', 'load' => $model->id]);?>" href="#">Loadsheet</a>
                    <a class="dropdown-item js-ajax-modal" data-url="<?= Url::toRoute(['bill-of-lading-pp', 'load' => $model->id]);?>" href="#">Bill of Lading - Plain Paper</a>
                    <a class="dropdown-item js-ajax-modal" data-url="<?= Url::toRoute(['bill-of-lading-ff', 'load' => $model->id]);?>" href="#">Bill of Lading - Form Feed</a>
                    <a class="dropdown-item js-ajax-modal" data-url="<?= Url::toRoute(['delivery-receipt', 'id' => $model->id]);?>" href="#">Delivery Receipt</a>
                    <a class="dropdown-item" href="<?= Url::toRoute(['transport-agreement', 'id' => $model->id]) ?>" target="_blank"><?= Yii::t('app', 'Transport Agreement') ?></a>
                    <a class="dropdown-item" href="<?= Url::toRoute(['accessorial-notification', 'id' => $model->id]) ?>" target="_blank">Accessorial Notification</a>
                </div>
            </div>
            <button class="btn btn-link js-ajax-modal" data-url="<?php echo Url::toRoute(['document/index', 'type' => 'load', 'id' => $model->id]) ?>" data-tooltip="tooltip" data-placement="top" title="<?php echo Yii::t('app', 'Images') ?>">
                <i class="fas fa-image"></i>
            </button>
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle" data-tooltip="tooltip" data-toggle="dropdown" data-placement="top" title="Mapping"><i class="fas fa-map"></i></button>
                <div class="dropdown-menu">
                    <a class="dropdown-item js-ajax-modal" href="#" data-url="<?= Url::toRoute(['route-map', 'id' => $model->id]) ?>"><?= Yii::t('app', 'Route Map PCMiler') ?></a>
                </div>
            </div>
            <button class="btn btn-link" disabled data-tooltip="tooltip" data-placement="top" title="Clipboard"><i class="fas fa-clipboard"></i></button>
            <button class="btn btn-link" disabled data-tooltip="tooltip" data-placement="top" title="Configure This Screen"><i class="fas fa-cog"></i></button>
        </div>
    </div>