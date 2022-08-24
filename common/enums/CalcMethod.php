<?php

namespace common\enums;

use TRS\Enum\Enum;

class CalcMethod extends Enum
{
    const PER_DAY = 'Per Day';
    const LOAD_MILES = 'Load Miles';
    const DRIVING_MILES = 'Driving Miles';
    const PERCENT = 'Percent';
}