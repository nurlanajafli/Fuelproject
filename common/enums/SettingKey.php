<?php

namespace common\enums;

use Yii;

class SettingKey extends BaseEnum
{
    const NOTIFICATION_CDL = 'notification_cdl';
    const NOTIFICATION_DOT = 'notification_dot';

    public static function getEnumLabels()
    {
        return [
            static::NOTIFICATION_CDL => Yii::t('app', 'Notify X days before CDL expiration'),
            static::NOTIFICATION_DOT => Yii::t('app', 'Notify X days before DOT physical text results expiration'),
        ];
    }
}
