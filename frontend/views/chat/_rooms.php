<?php
/**
 * @var array $rooms
 * @var string $id
 */

use yii\helpers\Url;

?>
<div class="chat__list">
  <div class="card">
    <div class="card-body">
      <div class="chat__list-inner">
        <?php foreach ($rooms as $room) : ?>
          <a href="<?= Url::toRoute(['view', 'id' => $room['id']]) ?>" class="chat__item<?= $room['id'] == $id ? ' active' : '' ?>">
            <div class="chat__item-info">
              <h6 class="user-name"><?= $room['title'] ?></h6>
              <p class="chat__item-text"><?= ($room['last_message']->isFromMe ? '<span class="user-sender">' . Yii::t('app', 'You') . ': </span>' : ($room['last_message']->load_id ? '<span class="user-sender">' . $room['last_message']->fromName . ': </span>' : '')) . $room['last_message']->message ?></p>
            </div>
            <?php if ($room['unread_count'] && $room['id'] != $id) : ?>
            <span class="badge badge-danger"><?= $room['unread_count'] ?></span>
            <?php endif; ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
