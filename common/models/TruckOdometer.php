<?php

namespace common\models;

use common\helpers\DateTime;
use common\models\base\TruckOdometer as BaseTruckOdometer;

/**
 * This is the model class for table "truck_odometer".
 */
class TruckOdometer extends BaseTruckOdometer
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }
}
