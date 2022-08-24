<?php

namespace common\models;

use Yii;
use \common\models\base\PurchaseOrderCode as BasePurchaseOrderCode;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "purchase_order_code".
 */
class PurchaseOrderCode extends BasePurchaseOrderCode
{

    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                # custom behaviors
            ]
        );
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                # custom validation rules
            ]
        );
    }
}
