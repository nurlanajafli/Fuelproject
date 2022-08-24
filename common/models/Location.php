<?php

namespace common\models;

use common\helpers\Utils;
use \common\models\base\Location as BaseLocation;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\base\Event;
use common\models\traits\Template;

/**
 * This is the model class for table "location".
 */
class Location extends BaseLocation
{
    use Template;

    const POINT_FORMAT_SHORT = 1;
    const POINT_FORMAT_LONG = 2;

    public function init()
    {
        parent::init();

        $this->on(static::EVENT_BEFORE_INSERT, [$this, 'eventBeforeSave']);
        $this->on(static::EVENT_BEFORE_UPDATE, [$this, 'eventBeforeSave']);
    }

    public function rules()
    {
        return ArrayHelper::merge(
            Utils::removeAttributeRules(parent::rules(), 'location', ['required']),
            [
                [['zone'], 'default', 'value' => null]
            ]
        );
    }

    protected static function eventBeforeSave(Event $event)
    {
        /** @var Location $model */
        $model = $event->sender;

        $model->location = new Expression("ST_SetSRID(ST_MakePoint({$model->lon},{$model->lat}),4326)");
    }

    public function attributeLabels()
    {
        return [
            'office_id' => \Yii::t('app', 'Office'),
        ];
    }

    public function getPoint(int $format = self::POINT_FORMAT_LONG)
    {
        $key1 = '';
        if ($format == self::POINT_FORMAT_SHORT) {
            $key1 = 'Lon';
            $key2 = 'Lat';
        } elseif ($format == self::POINT_FORMAT_LONG) {
            $key1 = 'longitude';
            $key2 = 'latitude';
        }

        return (!empty($this->lon) && $key1) ? [
            $key1 => $this->lon,
            $key2 => $this->lat
        ] : [];
    }
}
