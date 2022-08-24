<?php
/** @var \common\models\Document[] $models */
?>
<!DOCTYPE html>
<head>
  <meta charset="utf-8">
</head>
<body class="pdf-images">
    <?php foreach ($models as $model): ?>
    <div class="pdf-full"><img class="pdf-img" src="<?= $model->getUrl() ?>"></div>
    <?php endforeach; ?>
</body>