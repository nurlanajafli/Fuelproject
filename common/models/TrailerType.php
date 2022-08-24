<?php

namespace common\models;

use Yii;
use \common\models\base\TrailerType as BaseTrailerType;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "trailer_type".
 */
class TrailerType extends BaseTrailerType
{
    public function get_label()
    {
        return $this->type . " - " . $this->description;
    }
}
