<?php foreach (Yii::$app->session->allFlashes as $k => $v):
    $alertClass = 'alert-secondary';
    if ($k == 'error') $alertClass = 'alert-danger';
    if ($k == 'warning') $alertClass = 'alert-warning';
    if ($k == 'success') $alertClass = 'alert-success';
?>
  <div class="alert <?= $alertClass ?> alert-dismissible fade show" role="alert">
    <strong><?= ucfirst($k) ?></strong>! <?= $v[0] ?>
    <button type="button" class="close" data-dismiss="alert" aria-label="<?= Yii::t('app', 'Close') ?>">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
<?php endforeach; ?>