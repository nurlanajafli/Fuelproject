<?php
use yii\helpers\Html;
?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <?php $this->registerCsrfMetaTags(); ?>
        <?php $this->head(); ?>
        <title><?= Html::encode($this->title) ?></title>
    </head>
