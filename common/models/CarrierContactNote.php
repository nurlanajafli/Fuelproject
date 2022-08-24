<?php

namespace common\models;

use common\helpers\DateTime;
use common\models\base\CarrierContactNote as BaseCarrierContactNote;

/**
 * This is the model class for table "carrier_contact_note".
 */
class CarrierContactNote extends BaseCarrierContactNote
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }
}
