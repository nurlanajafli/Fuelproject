<?php

namespace common\models;

use common\enums\PartsCategoryEquip;
use Yii;
use \common\models\base\PartsCategory as BasePartsCategory;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "parts_category".
 */
class PartsCategory extends BasePartsCategory
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
                ['equip', 'in', 'range' => array_values(PartsCategoryEquip::getEnums())]
            ]
        );
    }
}
