<?php
/**
 * @var array $rooms
 * @var string $id
 * @var string $title
 * @var \common\models\ChatMessage[] $messages
 * @var \frontend\forms\chat\Send $sendModel
 */

use \yii\bootstrap4\ActiveForm;
?>
<div class="card shadow mb-4">
  <div class="card-body">
    <div class="row chat">
      <div class="col-4">
        <?= $this->render('_rooms', ['rooms' => $rooms, 'id' => $id]) ?>
      </div>
      <div class="col-8">
        <div class="card chat__messages">
          <div class="card-header">
            <div class="chat__user">
              <div class="chat__user-info">
                <h6 class="user-name"><?= $title ?></h6>
              </div>
            </div>
          </div>
          <div class="card-body">
            <?= $this->render('_messages', ['messages' => $messages]) ?>
          </div>
          <div class="card-footer">
              <?php
              $form = ActiveForm::begin();
              echo $form->field($sendModel, 'message')->textarea(['placeholder' => Yii::t('app', 'Your message')])->label(false);
              echo \yii\helpers\Html::button(Yii::t('app', 'Send'), ['class' => 'btn btn-primary btn-block', 'type' => 'submit']);
              ActiveForm::end();
              ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
