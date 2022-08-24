<?php

namespace common\helpers;

use yii\helpers\ArrayHelper;
use Yii;
use yii\base\InvalidConfigException;

class Hosts
{
    protected static function getHost($key)
    {
        $array = ArrayHelper::getValue(Yii::$app->params, 'hosts', []);
        if (!isset($array[$key]) || empty($array[$key])) {
            throw new InvalidConfigException("hosts > $key parameter is not set. See common/params-local.php config file.");
        }

        return $array[$key];
    }

    public static function getImageCdn()
    {
        return static::getHost('image-cdn');
    }
}
