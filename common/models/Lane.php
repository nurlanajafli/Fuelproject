<?php

namespace common\models;

use \common\models\base\Lane as BaseLane;

/**
 * This is the model class for table "lane".
 */
class Lane extends BaseLane
{
    public function beforeSave($insert)
    {
        if (!$this->origin_zone) {
            $this->origin_zone = null;
        }
        if (!$this->destination_zone) {
            $this->destination_zone = null;
        }
        return parent::beforeSave($insert);
    }
}
