<?php
/**
 * Date: 8/24/16
 * Time: 8:36 PM
 */

namespace common\enums;


use TRS\Enum\Enum;

/**
 * @deprecated Yii::t is not allows to use constants
 */
class I18nCategory extends Enum
{
    const APP          = 'app';
    const ERROR        = 'app/error';
//    const MODEL_FIELDS = 'app/model/field';
//    const LAYOUT       = 'app/layout';
//    const TITLE        = 'app/title';
//    const ENUM         = 'app/enum';
}