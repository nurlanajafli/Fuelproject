<?php

namespace common\enums;


use TRS\Enum\Enum;

class PayStandard extends Enum
{
    const HOURLY = 'Hourly';
    const SALARY = 'Salary';
    const NEITHER = 'Neither';
    const BOTH = 'Both';
}