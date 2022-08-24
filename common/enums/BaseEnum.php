<?php

namespace common\enums;

use Yii;
use TRS\Enum\Enum;

class BaseEnum extends Enum
{
    public static function getUiEnums($localized = true)
    {
        $data = [];
        foreach (array_flip(self::getEnums()) as $key => $value)
            $data[$key] = $localized ? Yii::t('app', $key) : $key;
        return $data;
    }
}