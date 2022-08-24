<?php

namespace common\models;

use common\helpers\DateTime;
use common\models\base\VendorContactNote as BaseVendorContactNote;

/**
 * This is the model class for table "vendor_contact_note".
 */
class VendorContactNote extends BaseVendorContactNote
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }
}
