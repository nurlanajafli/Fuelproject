<?php

namespace common\models;

use \common\models\base\PaymentTermCode as BasePaymentTermCode;
use common\helpers\DateTime;

/**
 * This is the model class for table "payment_term_code".
 */
class PaymentTermCode extends BasePaymentTermCode
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }
}
