<?php

namespace common\models;

use Yii;
use \common\models\base\LanePreference as BaseLanePreference;

/**
 * This is the model class for table "lane_preference".
 */
class LanePreference extends BaseLanePreference
{
    public function attributeLabels()
    {
        $attributeLabels = parent::attributeLabels();
        $attributeLabels['forty_eight_states'] = Yii::t('app', '48 States');
        $attributeLabels['state_id'] = Yii::t('app', 'Base State');
        return $attributeLabels;
    }
}
