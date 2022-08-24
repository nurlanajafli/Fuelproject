<?php
$root = dirname($s = dirname(__DIR__));
Yii::setAlias('@common', $s);

foreach (['backend', 'cdn', 'console', 'frontend', 'garage', 'markup', 'v1'] as $app) {
    Yii::setAlias("@$app", "$root/$app");
}

Yii::setAlias("@cdn-webroot", "@cdn/web");
