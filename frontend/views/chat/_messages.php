<?php
/**
 * @var \common\models\ChatMessage[] $messages
 */
?>
    <?php $currentDate = null; foreach ($messages as $message) :
      $string = Yii::$app->formatter->asDate($message->created_at, Yii::$app->params['formats']['db']);
      if ($currentDate != $string) {
        $currentDate = $string;
        $date = Yii::$app->formatter->asDate($message->created_at, Yii::$app->params['formats'][12]) . \common\helpers\Utils::ordinal(Yii::$app->formatter->asDate($message->created_at, 'd'));
        $dateTime = date_create('now', timezone_open(Yii::$app->formatter->timeZone));
        if ($currentDate == Yii::$app->formatter->asDate($dateTime, Yii::$app->params['formats']['db'])) {
          $date = Yii::t('app', 'Today');
        }
        $dateTime->sub(new DateInterval('P1D'));
        if ($currentDate == Yii::$app->formatter->asDate($dateTime, Yii::$app->params['formats']['db'])) {
          $date = Yii::t('app', 'Yesterday');
        }
        echo '<div class="chat__message-date"><span>' . $date . '</span></div>';
      }
      ?>
    <div class="clearfix">
      <div class="chat__message float-<?= $message->isFromMe ? 'right' : 'left' ?>">
        <div class="chat__message-author"><span><?= $message->fromName ?></span><span><?= Yii::$app->formatter->asDate($message->created_at, Yii::$app->params['formats'][11]) ?></span></div>
        <div class="chat__message-text">
          <p><?= $message->message ?></p>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
