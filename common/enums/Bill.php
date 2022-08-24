<?php

namespace common\enums;


use TRS\Enum\Enum;

class Bill extends Enum
{
    const BILL_TO = 'B';
    const PREPAID = 'P';
    const COLLECT = 'C';
}