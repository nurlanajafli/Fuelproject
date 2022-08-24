<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var array $options
 */

$iconHtml = $options['icon'] ?
    $options['icon']['before'].Html::tag($options['icon']['tag'], '', ['class'=>$options['icon']['class'].$options['icon']['name']]).$options['icon']['after'] :
    '';
echo Html::a(
    $iconHtml.(strpos($options['a']['text'], '/>') ? $options['a']['text'] : ($options['icon'] ? ' ' : '').$options['a']['text']),
    Url::to($options['a']['url']),
    $options['a']['options']
);