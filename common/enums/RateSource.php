<?php

namespace common\enums;

use TRS\Enum\Enum;

class RateSource extends Enum
{
    const MANUAL = 'Manual';
    const LOAD_MATRIX = 'Load Matrix';
    const COMMODITY_MATRIX = 'Commodity Matrix';
}