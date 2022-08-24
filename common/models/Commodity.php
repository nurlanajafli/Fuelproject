<?php

namespace common\models;

use common\enums\CommodityType;
use Yii;
use \common\models\base\Commodity as BaseCommodity;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "commodity".
 */
class Commodity extends BaseCommodity
{
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['type'], 'in', 'range' => CommodityType::getEnums()]
            ]
        );
    }

    public function beforeSave($insert)
    {
        if (!$this->hazmat_code) {
            $this->hazmat_code = null;
        }
        return parent::beforeSave($insert);
    }
}
