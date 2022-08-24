<?php

namespace common\models;

use common\helpers\DateTime;
use common\models\base\Department as BaseDepartment;

/**
 * This is the model class for table "department".
 *
 * @property-read string $code
 */
class Department extends BaseDepartment
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function get_label()
    {
        return $this->name;
    }

    public function getCode()
    {
        return strtoupper(substr($this->name, 0, 2));
    }
}
