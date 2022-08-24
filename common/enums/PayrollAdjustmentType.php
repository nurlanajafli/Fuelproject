<?php


namespace common\enums;


use TRS\Enum\Enum;

class PayrollAdjustmentType extends Enum
{
    const LOCAL_INCOME_TAX = "Local Income Tax";
    const NON_TAX_ADJUSTMENT = "Non-Tax Adjustment";
}