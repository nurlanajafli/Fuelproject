<?php
/**
 * @var array $rooms
 */
?>
<div class="card shadow mb-4">
  <div class="card-body">
    <div class="row chat">
      <div class="col-4">
        <?= $this->render('_rooms', ['rooms' => $rooms, 'id' => '']) ?>
      </div>
      <div class="col-8"></div>
    </div>
  </div>
</div>
