<?php

namespace common\enums;

use TRS\Enum\Enum;

class BillStatusFilter extends Enum
{
    const ALL = 'All';
    const PAID = 'Paid';
    const OPEN_ALL = 'OpenAll';
    const OPEN_BY_DATE = 'OpenByDate';
    const SHORT_PAID = 'ShortPaid';
    const PAST_DUE = 'PastDue';
}