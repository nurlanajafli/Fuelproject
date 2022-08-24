<?php
/**
 * /var/www/html/frontend/runtime/giiant/f7eb1babf3c279e06df20ae63d4bed57
 *
 * @package default
 */

use common\enums\I18nCategory;
use yii\helpers\Inflector;

/**
 *
 * @var yii\web\View $this
 * @var string $modelName
 */
?>
<div class="card-header py-3">
    <div class="edit-form-toolbar">
        <button class="btn btn-link disabled" data-tooltip="tooltip" data-placement="top" title="<?=Yii::t('app', 'New')?>"><i class="fas fa-plus"></i></button>
        <button class="btn btn-link disabled" data-tooltip="tooltip" data-placement="top" title="<?= Yii::t('app', 'Images') ?>"><i class="fas fa-image"></i></button>
        <button class="btn btn-link disabled" data-tooltip="tooltip" data-placement="top" title="<?= Yii::t('app', 'Terminate '.Inflector::camel2words($modelName)) ?>"><i class="fas fa-eraser"></i></button>
    </div>
</div>
