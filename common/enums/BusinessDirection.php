<?php

namespace common\enums;


use TRS\Enum\Enum;

class BusinessDirection extends Enum
{
    const TRUCKLOAD_DISPATCH = 1;
    const TRUCKLOAD_BROKERAGE = 2;
    const LTL_DISPATCH = 3;
    const LTL_BROKERAGE = 4;
    const INTERMODAL_DISPATCH = 5;
    const INTERMODAL_BROKERAGE = 6;
}