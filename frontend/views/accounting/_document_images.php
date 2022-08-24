<?php
/**
 * @var \common\models\Document[] $documents
 */
?>
<!DOCTYPE html>
<head>
  <meta charset="utf-8">
</head>
<body>
<div class="container">
  <div class="container">
    <div class="doc__title border-y">
      <h1 class="text-center">Upload documents</h1>
    </div>
      <?php foreach ($documents as $document) : ?>
        <p class="text-center"><?= $document->description ?></p>
        <p style="text-center">
          <img src="<?= $document->getUrl() ?>" style="max-width:100%; margin:0 auto; display:block;">
        </p>
        <hr>
        <br>
      <?php endforeach; ?>
  </div>
</div>
</body>

