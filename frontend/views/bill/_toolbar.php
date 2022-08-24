<?php
/**
 * @var yii\web\View $this
 * @var common\models\Bill $model
 */

use yii\helpers\Url;

?>
  <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Plus"><i class="fas fa-plus"></i>
  </button>
  <button class="btn btn-link js-show-load-cleaning" data-tooltip="tooltip" data-placement="top" title="Left"><i
            class="fas fa-caret-left"></i></button>
  <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Right"><i
            class="fas fa-caret-right"></i></button>
  <button class="btn btn-link js-ajax-modal" data-url="<?= Url::toRoute(['adjust-balance', 'id' => $model->id]) ?>" data-tooltip="tooltip" data-placement="top" title="Edit"><i class="fas fa-pen"></i>
  </button>
  <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Microscope"><i
            class="fas fa-microscope"></i></button>
  <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Download"><i
            class="fas fa-download"></i></button>
  <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Print"><i class="fas fa-print"></i>
  </button>
  <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Lamp"><i class="fas fa-lightbulb"></i>
  </button>
  <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Image"><i class="fas fa-image"></i>
  </button>
  <button class="btn btn-link" data-tooltip="tooltip" data-placement="top" title="Clipboard"><i
            class="fas fa-clipboard"></i></button>
<?php if (Yii::$app->user->can(\common\enums\Permission::ADD_EDIT_BILLS) && $model->canDelete()): ?>
  <button class="btn btn-link js-bill-delete" data-confirm-text="<?= Yii::t('app', 'Are you sure to delete this item?') ?>" data-tooltip="tooltip" data-placement="top" title="<?= Yii::t('app', 'Delete') ?>">
    <i class="fas fa-times"></i>
  </button>
<?php endif;