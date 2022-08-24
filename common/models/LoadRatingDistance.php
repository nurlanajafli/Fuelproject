<?php

namespace common\models;

use common\enums\LoadRateType;
use Yii;
use \common\models\base\LoadRatingDistance as BaseLoadRatingDistance;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "load_rating_distance".
 */
class LoadRatingDistance extends BaseLoadRatingDistance implements LoadRatingMethodInterface
{
    const FLAT  = LoadRateType::FLAT;
    const MILES = LoadRateType::MILES;
    const TON   = LoadRateType::TON;

    public function scenarios()
    {
        return self::getFieldsByType();
    }

    public static function getFieldsByType()
    {
        $last = ['description'];

        return [
            self::FLAT  => array_merge(['low_miles', 'high_miles', 'rate'], $last),
            self::MILES => array_merge(['low_miles', 'high_miles', 'rate'], $last),
            self::TON   => array_merge(['low_miles', 'high_miles', 'rate'], $last),
        ];
    }

    public function rules()
    {
        return array_merge(
            parent::rules(), [
                [['low_miles', 'high_miles'], 'required', 'on' => [self::FLAT, self::MILES, self::TON]],
            ]
        );
    }

    public function attributeHints()
    {
        return array_merge(parent::attributeHints(), [
            'rate' => false,
            'low_miles' => Yii::t('app', 'to specify a Minimum Rate set `Low Miles` = 0, the rate will be treated as flat rate'),
        ]);
    }

    public static function getColumns($rateType)
    {
        return self::getFieldsByType()[$rateType];
    }

    public function getFieldParams($model, $field)
    {
        switch ($field) {
            case 'description':
                return ['maxlength' => true];
            case 'rate':
                return ['type' => 'number', 'min' => 0, 'step' => 0.01, 'value' => $model->isNewRecord ? 0 : $model->$field];
            case 'low_miles':
            case 'high_miles':
                return ['type' => 'number', 'min' => 0];
        }
    }
}
