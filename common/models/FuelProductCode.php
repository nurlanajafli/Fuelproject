<?php

namespace common\models;

use common\models\base\FuelProductCode as BaseFuelProductCode;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "fuel_product_codes".
 */
class FuelProductCode extends BaseFuelProductCode
{
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['oo_acct', 'cd_acct', 'fee_acct'], 'default', 'value' => null]
        ]);
    }
}
