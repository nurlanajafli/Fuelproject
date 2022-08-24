<?php

namespace frontend\forms\load;

use common\enums\DeliveryReceiptTypes;
use yii\base\Model;
use Yii;

class DeliveryReceipt extends Model
{
    public $type;

    public function rules()
    {
        return [
            [['type'], 'required'],
            [['type'], 'string'],
            [['type'], 'in', 'range' => DeliveryReceiptTypes::getEnums()],
        ];
    }

    public function attributeLabels()
    {
        return [
            'type' => Yii::t('app', 'Type'),
        ];
    }
}