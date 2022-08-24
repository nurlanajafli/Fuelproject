<?php
use yii\helpers\Url;
?>
<button disabled class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="">
    <i class="fas fa-sync"></i>
</button>
<button disabled class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="">
    <i class="fas fa-equals"></i>
</button>
<div class="btn dropdown">
    <button class="btn btn-link dropdown-toggle" data-tooltip="tooltip" data-placement="top" data-toggle="dropdown">
        <i class="fas fa-plus-circle"></i>
    </button>
    <div class="dropdown-menu">
        <a href="<?= Url::toRoute(['create']) ?>" class="dropdown-item"><?= Yii::t('app', 'Unit') ?></a>
        <a href="<?= Url::toRoute(['truck/create']) ?>" class="dropdown-item"><?= Yii::t('app', 'Truck') ?></a>
        <a href="<?= Url::toRoute(['trailer/create']) ?>" class="dropdown-item"><?= Yii::t('app', 'Trailer') ?></a>
    </div>
</div>