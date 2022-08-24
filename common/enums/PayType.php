<?php

namespace common\enums;


use TRS\Enum\Enum;

class PayType extends Enum
{
    const FLAT = 'Flat';
    const MILES = 'Miles';
    const PERCENT = 'Percent';
    const HOURLY = 'Hourly';
}