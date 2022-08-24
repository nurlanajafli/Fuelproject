<?php

namespace common\models;

use common\helpers\DateTime;
use common\models\base\CustomerContactNote as BaseCustomerContactNote;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "customer_contact_note".
 */
class CustomerContactNote extends BaseCustomerContactNote
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['code'], 'default', 'value' => null]
        ]);
    }

    public function get_label()
    {
        return $this->code;
    }
}
