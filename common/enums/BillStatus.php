<?php

namespace common\enums;

use TRS\Enum\Enum;

class BillStatus extends Enum
{
    const PAID = 'Paid';
    const OPEN = 'Open';
    const SHORT_PAID = 'Short Paid';
    const PAST_DUE = 'Past Due';
}
