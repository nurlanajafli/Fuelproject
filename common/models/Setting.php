<?php

namespace common\models;

use common\enums\SettingKey;
use common\helpers\DateTime;
use common\models\base\Setting as BaseSetting;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "setting".
 */
class Setting extends BaseSetting
{
    public function behaviors()
    {
        return DateTime::setLocalTimestamp(parent::behaviors(), []);
    }

    public function rules()
    {
        $options = static::options();
        $array = [
            [['key'], 'in', 'range' => SettingKey::getEnums()]
        ];
        foreach ($options as $option) {
            foreach ($option[1] as $validator) {
                $array[] = ArrayHelper::merge(
                    [['value']],
                    is_array($validator) ? $validator : [$validator],
                    [
                        'when' => function ($model) use ($option) {
                            return ArrayHelper::isIn($model->key, $option[0]);
                        },
                        'whenClient' => 'function (attribute, value) {
                            return ["' . join('", "', $option[0]) . '"].includes(attribute.input.substr(14));
                        }'
                    ]
                );
            }
        }
        return ArrayHelper::merge(parent::rules(), $array);
    }

    public static function options()
    {
        return [
            [[SettingKey::NOTIFICATION_CDL, SettingKey::NOTIFICATION_DOT], ['required', ['integer', 'integerOnly' => true, 'min' => 0]]]
        ];
    }

    protected static function defaults()
    {
        return [
            SettingKey::NOTIFICATION_CDL => 14,
            SettingKey::NOTIFICATION_DOT => 14
        ];
    }

    public static function map()
    {
        $array = ArrayHelper::merge(static::defaults(), ArrayHelper::map(Setting::find()->all(), 'key', 'value'));

        $labels = SettingKey::getEnumLabels();
        asort($labels, SORT_STRING);

        $result = [];

        foreach ($labels as $key => $value) {
            $result[] = [$value, $array[$key] ?? '', $key];
        }

        return $result;
    }

    public static function get($key)
    {
        $defaults = static::defaults();
        $model = static::findOne(['key' => $key]);
        return $model ? $model->value : $defaults[$key] ?? null;
    }
}
