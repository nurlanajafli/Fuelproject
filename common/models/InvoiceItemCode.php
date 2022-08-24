<?php

namespace common\models;

use Yii;
use \common\models\base\InvoiceItemCode as BaseInvoiceItemCode;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "invoice_item_codes".
 */
class InvoiceItemCode extends BaseInvoiceItemCode
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
