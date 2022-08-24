<?php

namespace common\models;

use \common\models\base\Zone as BaseZone;

/**
 * This is the model class for table "zone".
 */
class Zone extends BaseZone
{
    public function get_label()
    {
        return $this->code . " - " . $this->description;
    }
}
