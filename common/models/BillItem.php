<?php

namespace common\models;

use common\helpers\DateTime;
use common\models\base\BillItem as BaseBillItem;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "bill_item".
 */
class BillItem extends BaseBillItem
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors());
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['account', 'default', 'value' => null]
        ]);
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'driver_id' => Yii::t('app', 'Charge To'),
        ]);
    }
}
