<?php


namespace common\enums;
use TRS\Enum\Enum;
use yii\base\InvalidArgumentException;


class BillOfLadingType extends Enum
{
    //First type
    const SHOW_FREIGHT_CHARGES = 'Show freight charges';
    const HIDE_FREIGHT_CHARGES = 'Hide freight charges';
    //Second type
    const STANDART_VIEW = 'Standart view';
    const PREMIUM_VIEW = 'Premium view';
    //Third type
    const SHOW_BILLING_NOTICE = 'Show billing notice';
    const HIDE_BILLING_NOTICE = 'Hide billing notice';
    //Fourth type
    const SHOW_CARRIER_NAME = 'Show carrier name';
    const HIDE_CARRIER_NAME = 'Hide carrier name';
    //Fifth type
    const SHOW_PHONE_NUMBERS = 'Show phone numbers';
    const HIDE_PHONE_NUMBERS = 'Hide phone numbers';
}