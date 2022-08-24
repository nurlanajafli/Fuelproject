<?php

namespace common\helpers;

use Yii;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\FormatConverter;

class DateTime
{
    public static function nowDateYMD()
    {
        return strftime("%F"); // Same as "%Y-%m-%d" (commonly used in database datestamps) Example: 2009-02-05
    }

    public static function yerterdayDateYMD()
    {
        return strftime("%F", time() - 24*3600); // Same as "%Y-%m-%d" (commonly used in database datestamps) Example: 2009-02-05
    }

    public static function nowTime()
    {
        return strftime("%R"); // Same as "%H:%M", Example: 00:35 for 12:35 AM, 16:44 for 4:44 PM
    }

    public static function setLocalTimestamp($behaviors, $attributes = [])
    {
        return array_map(function ($array) use ($attributes) {
            if ($array['class'] == \yii\behaviors\TimestampBehavior::className()) {
                $array['value'] = new \yii\db\Expression('LOCALTIMESTAMP');
                $array['attributes'] = ArrayHelper::merge([
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ArrayHelper::getValue($array, 'createdAtAttribute', 'created_at'),
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ArrayHelper::getValue($array, 'updatedAtAttribute', 'updated_at')
                ], $attributes);
            }
            return $array;
        }, $behaviors);
    }

    /**
     * @param string $tz 'EDT'
     * @param string $date '2021-04-20'
     * @param string $time '18:00'
     * @return string 2021-04-03 04:00:00+00
     * @throws \yii\db\Exception
     */
    public static function fromTzToLocal($tz, $date, $time)
    {
        $result = null;

        if (!empty($tz)) {
            $connection = \Yii::$app->getDb();
            $command = $connection->createCommand("SELECT timezone(:tz, :fd::date + :ft::time) as local_dt", [
                ':tz' => $tz, ':fd' => $date, ':ft' => $time
            ]);
            $result = $command->queryScalar();
        }

        return $result;
    }

    /**
     * Minutes left till now()
     * @param string $tz 'EDT'
     * @param string $date '2021-04-20'
     * @param string $time '18:00'
     * @return string 2021-04-03 04:00:00+00
     * @throws \yii\db\Exception
     */
    public static function minutesLeftTillNowFromTzToLocal($tz, $date, $time)
    {
        $result = null;

        if (!empty($tz)) {
            $connection = \Yii::$app->getDb();
            $command = $connection->createCommand("SELECT EXTRACT(EPOCH FROM timezone(:tz, :fd::date + :ft::time) - now())/60 as minutes", [
                ':tz' => $tz, ':fd' => $date, ':ft' => $time
            ]);
            $result = $command->queryScalar();
        }

        return $result;
    }

    public static function transformDate($date, $formatSrc, $formatDest)
    {
        return Yii::$app->formatter->asDate(\DateTime::createFromFormat(FormatConverter::convertDateIcuToPhp($formatSrc), $date), $formatDest);
    }
}