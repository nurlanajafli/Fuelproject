<?php

namespace common\enums;

use TRS\Enum\Enum;

class WorkOrderStatus extends Enum
{
    const DRAFT = 'draft';
    const OPEN = 'open';
    const COMPLETED = 'completed';
}