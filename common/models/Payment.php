<?php

namespace common\models;

use common\helpers\DateTime;
use common\models\base\Payment as BasePayment;

/**
 * This is the model class for table "payment".
 */
class Payment extends BasePayment
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }
}
