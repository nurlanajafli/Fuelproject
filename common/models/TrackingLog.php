<?php

namespace common\models;

use \common\models\base\TrackingLog as BaseTrackingLog;

/**
 * This is the model class for table "tracking_log".
 */
class TrackingLog extends BaseTrackingLog
{
    public function behaviors()
    {
        return array_map(function ($array) {
            if ($array['class'] == \yii\behaviors\TimestampBehavior::className()) {
                $array['value'] = new \yii\db\Expression('current_timestamp');
            }
            return $array;
        }, parent::behaviors());
    }

    public function beforeSave($insert)
    {
        if (!$this->zone) {
            $this->zone = null;
        }
        return parent::beforeSave($insert);
    }

    /**
     * @param $id
     * @return Location|null
     */
    public static function getLatestUnitLocation($id)
    {
        return self::getLatestLocation('unit_id', $id);
    }

    /**
     * @param $id
     * @return Location|null
     */
    public static function getLatestTruckLocation($id)
    {
        return self::getLatestLocation('truck_id', $id);
    }

    /**
     * @param $id
     * @return Location|null
     */
    public static function getLatestTrailerLocation($id)
    {
        return self::getLatestLocation('trailer_id', $id);
    }

    /**
     * @param $vehicleType
     * @param $vehicleId
     * @return Location|null
     */
    private static function getLatestLocation($vehicleType, $vehicleId) {
        $res = null;
        $model = self::find()
            ->where([$vehicleType => $vehicleId])
            ->orderBy(['date' => SORT_DESC, 'id' => SORT_DESC])
            ->one();

        if ($model)
            $res = $model->location;

        return $res;
    }
}
