<?php

namespace common\models;

use common\helpers\DateTime;
use common\models\base\ViolationMessage as BaseViolationMessage;

/**
 * This is the model class for table "violation_message".
 */
class ViolationMessage extends BaseViolationMessage
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }
}
