<?php

namespace common\enums;

class DateType extends BaseEnum
{
    const SYSTEM_DATE = 'System Date';
    const PICKUP_DATE = 'Pickup Date';
    const DELIVERY_DATE = 'Delivery Date';
    const USER_DEFINED_DATE = 'User-Defined Date';
}