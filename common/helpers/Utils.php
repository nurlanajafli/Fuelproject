<?php

namespace common\helpers;

use Yii;
use yii\helpers\ArrayHelper;

class Utils
{
    public static function removeAttributeRules(array $rules, string $attribute, array $validators = array())
    {
        $result = [];
        foreach ($rules as $key => $rule) {
            if (is_array($rule[0])) {
                if (($index = array_search($attribute, $rule[0])) !== false && in_array($rule[1], $validators)) {
                    if (count($rule[0]) == 1) {
                        continue;
                    } else {
                        unset($rule[0][$index]);
                    }
                }
            } else if ($rule[0] == $attribute && in_array($rule[1], $validators)) {
                continue;
            }
            $result[] = $rule;
        }
        return $result;
    }

    public static function ordinal($i)
    {
        $j = $i % 100;
        if (($j >= 11) && ($j <= 13)) {
            return 'th';
        }

        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
        return $ends[$i % 10];
    }

    public static function abbreviation($word, $messageCategory = null)
    {
        $result = static::getParam(['abbreviations', $word], $word);
        return $messageCategory ? Yii::t($messageCategory, $result) : $result;
    }

    public static function getParam($key, $default = null)
    {
        return ArrayHelper::getValue(Yii::$app->params, $key, $default);
    }

    public static function yn($b, $short = true)
    {
        $answers = [['Y', 'Yes'], ['N', 'No']];
        return $answers[$b ? 0 : 1][!$short + 0];
    }

    public static function jsName()
    {
        return uniqid('auto');
    }
}