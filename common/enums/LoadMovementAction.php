<?php

namespace common\enums;

use TRS\Enum\Enum;

class LoadMovementAction extends Enum
{
    const PICKUP = 'Pickup';
    const DROP = 'Drop';
    const DELIVER = 'Deliver';
}