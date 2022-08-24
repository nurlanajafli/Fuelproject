<?php

namespace common\enums;

use TRS\Enum\Enum;

class PayCode extends Enum
{
    const LOAD = "Load";
    const PICKUP = "Pickup";
    const DELIVERY = "Delivery";
}