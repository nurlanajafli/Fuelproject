<?php

namespace common\helpers;

use yii\base\Event;
use yii\helpers\ArrayHelper;

class EventHelper
{
    public static function attributeIsChanged(Event $event, string $attribute)
    {
        return isset($event->changedAttributes)
            && ArrayHelper::keyExists($attribute, $event->changedAttributes)
            && ($event->sender->$attribute != ArrayHelper::getValue($event->changedAttributes, $attribute));
    }
}
